import {Module, Mutation, Action, VuexModule} from "vuex-module-decorators"
import {$axios} from "../plugins/nuxt-axios-exporter"
import {GModServer, GModServerTransfer} from "../core/Entities"

interface ApiData {
    servers: GModServerTransfer[];
}
@Module({namespaced: true, name: "gmodServers"})
export default class GModServersModule extends VuexModule {
    isInitialized: boolean = false;
    variable = "butts";
    gmServers: GModServer[] = [];
    @Mutation
    private setInitialized(value: boolean) {
        this.isInitialized = value;
    }
    @Mutation
    set(v:string) {
        this.variable = v;
    }
    @Mutation
    private setServers(servers: GModServer[]) {
        this.gmServers = servers;
    }

    @Mutation
    private addServer(server: GModServer) {
        this.gmServers.push(server);
    }
    @Action({rawError: true})
    async initalize() {
        if (!this.isInitialized) {
            const data: ApiData = await $axios.$get("http://abso.gg/api/areas");
            const servers = data.servers.map(serverxf => GModServer.hydrate(serverxf));
            this.setServers(servers);
            this.setInitialized(true);
        }
    }
}
