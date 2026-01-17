import {Module, Mutation, Action, VuexModule} from "vuex-module-decorators"
import {$axios} from "../plugins/nuxt-axios-exporter"
import {$echo} from "../plugins/ably-echo"
import {GModBansTransfer, GModBans, PaginationLinksTransfer, PaginationLinks, GModBansAuditsValuesTransfer, GModBansAuditsValues, GModBansAuditsTransfer, GModBansAudits} from "../core/Entities"

interface ApiData {
    audits: GModBansAuditsTransfer[];
}
interface SuccessData {
    success: boolean;
}


@Module({namespaced: true, name: "gmodBansAudits", stateFactory: true})
export default class gmodBansAuditsModule extends VuexModule {
    isInitialized = false;
    page = 1;
    length = 1;
    total = 0;
    bans: GModBans[] = [];
    links: PaginationLinks[] = [];
    
    audits: GModBansAudits[] = [];
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

    @Mutation
    private setAudits(audits: GModBansAudits[]) {
        this.audits = audits;
    }
    @Action({ rawError: true })
    async getAudits(payload: { banID: number; Reason?: boolean; ExpiryDate?: boolean; RevokeReason?: boolean; }) {
        const { banID, Reason, ExpiryDate, RevokeReason } = payload;
        const data: ApiData = {
        audits: await $axios.$get(
            `http://abso.gg/api/ban-audits/${banID}`,
            {
            params: {
                Reason,
                ExpiryDate,
                RevokeReason
            }
            }
        )
        };
        const audits = data.audits.map(serverxf => GModBansAudits.hydrate(serverxf));
        this.setAudits(audits);
    }
}
