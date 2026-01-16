import {Module, Mutation, Action, VuexModule} from "vuex-module-decorators"
import {$axios} from "../plugins/nuxt-axios-exporter"
import {$echo} from "../plugins/ably-echo"
import {GModBansTransfer, GModBans, PaginationLinksTransfer, PaginationLinks} from "../core/Entities"

interface ApiData {
    current_page: number;
    data: GModBansTransfer[];
    first_page_url: string;
    last_page_url: string;
    next_page_url: string;
    prev_page_url: string;
    from: number;
    to: number;
    total: number;
    last_page: number;
    per_page: number;
    links: PaginationLinksTransfer[];
}
interface SuccessData {
    success: boolean;
}
interface ServerPOJO {
    id: number;
    name: string;
    ip: string;
    port: number;
    map: string;
    gamemode: string;
    online: number;
    max_online: number;
}

@Module({namespaced: true, name: "gmodBans", stateFactory: true})
export default class GModBansModule extends VuexModule {
    isInitialized = false;
    page = 1;
    length = 1;
    total = 0;
    bans: GModBans[] = [];
    links: PaginationLinks[] = [];

    @Mutation
    private setInitialized(value: boolean) {
        this.isInitialized = value;
    }

    @Mutation
    private setBans(bans: GModBans[]) {
        this.bans = bans;
    }
    @Mutation
    private setPage(page: number) {
        this.page = page;
    }
    @Mutation
    private setLength(length: number) {
        this.length = length;
    }
    @Mutation
    private setTotal(total: number) {
        this.total = total;
    }
    @Mutation
    private setLinks(links: PaginationLinks[]) {
        this.links = links;
    }

    /*@Action({rawError: true})
    subscribe() {
        $echo.channel('gm_live_servers')
        .listen('.UpdateGModServerStats', (event: any) => {
          console.log('Server update received:', event.servers)
          this.rehydrate(event.servers);
        })
    }*/

    @Action({rawError: true})
    async initalize() {
        if (!this.isInitialized) {
            const data: ApiData = await $axios.$get("http://abso.gg/api/bans?page=1");
            const bans = data.data.map(serverxf => GModBans.hydrate(serverxf));
            const links = data.links.map(serverxf => PaginationLinks.hydrate(serverxf));
            this.setLength(data.last_page);
            this.setTotal(data.total);
            this.setBans(bans);
            this.setLinks(links);
            this.setInitialized(true);
        }
    }

    @Action({ rawError: true })
    async gotoPage(payload: { page: number; items: number; search?: string }) {
        const { page, items, search } = payload;
        const data: ApiData = await $axios.$get('http://abso.gg/api/bans', {
            params: {
            page,
            items,
            ...(search ? { search } : {}),
            },
        })
        const bans = data.data.map(serverxf => GModBans.hydrate(serverxf));
        const links = data.links.map(serverxf => PaginationLinks.hydrate(serverxf));
        this.setLength(data.last_page);
        this.setTotal(data.total);
        this.setBans(bans);
        this.setLinks(links);
    }

    @Action({ rawError: true })
    async changeBanReason(payload: { banID: number; newReason: string }) {
        const { banID, newReason } = payload;

        const data: SuccessData = await $axios.$post('http://abso.gg/api/changereason', {
            banID,
            newReason
        });

        return data.success;
    }

}
