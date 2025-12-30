import {Module, Mutation, Action, VuexModule} from "vuex-module-decorators"
import {$axios} from "../plugins/nuxt-axios-exporter"
import {$echo} from "../plugins/ably-echo"
import {CommunityStat, CommunityStatTransfer} from "../core/Entities"

interface ApiData {
    stats: CommunityStatTransfer;
}

@Module({namespaced: true, name: "communityStats", stateFactory: true})
export default class CommunityStatsModule extends VuexModule {
    isInitialized = false;
    stats: CommunityStat | null = null

    @Mutation
    private setInitialized(value: boolean) {
        this.isInitialized = value;
    }

    @Mutation
    private SetStats(stats: CommunityStat) {
        this.stats = stats;
    }

    @Action({rawError: true})
    subscribe() {
        $echo.channel('live_community_stats')
        .listen('.UpdateCommunityStats', (event: any) => {
          console.log('Stats update received:', event.stats)
          this.SetStats(event.stats);
        })
    }

    @Action({rawError: true})
    async initalize() {
        if (!this.isInitialized) {
            const data: ApiData = await $axios.$get("http://abso.gg/api/communitystats");
            const stats = CommunityStat.hydrate(data.stats);
            this.SetStats(stats);
            this.setInitialized(true);
        }
    }
}
