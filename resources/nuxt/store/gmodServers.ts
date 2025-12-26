import {Module, Mutation, Action, VuexModule} from "vuex-module-decorators"
import {$axios} from "../plugins/nuxt-axios-exporter"
import {$echo} from "../plugins/ably-echo"
import {GModServer, GModServerTransfer} from "../core/Entities"

interface ApiData {
    servers: GModServerTransfer[];
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

@Module({namespaced: true, name: "gmodServers", stateFactory: true})
export default class GModServersModule extends VuexModule {
    isInitialized: boolean = false;
    variable = "butts";
    gmServers: ServerPOJO[] = [];
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

    @Mutation
    public rehydrate(servers: GModServer[]) {
        this.gmServers = servers;
    }

    @Action({rawError: true})
    subscribe() {
        $echo.channel('gm_live_servers')
        .listen('.UpdateGModServerStats', (event: any) => {
          console.log('Server update received:', event.servers)
          this.rehydrate(event.servers);
        })
    }

    @Action({rawError: true})
    async initalize() {
        if (!this.isInitialized) {
            console.log("we doin it live");
            const data: ApiData = await $axios.$get("http://abso.gg/api/areas");
            const servers = data.servers.map(serverxf => GModServer.hydrate(serverxf));
            this.setServers(servers);
            this.setInitialized(true);
        }
    }
}
