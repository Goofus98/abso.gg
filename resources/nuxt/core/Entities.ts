export interface GModServerTransfer {
    id: number;
    name: string;
    ip: string;
    port: number;
    map: string;
    gamemode: string;
    online: number;
    max_online: number;
}

export class GModServer {
    //public instances: GModServerInstance[] = [];

    constructor(public id: number,
        public name:string,
        public ip:string,
        public port:number,
        public map:string,
        public gamemode:string, 
        public online:number,
        public max_online:number){}

    static hydrate(xf: GModServerTransfer): GModServer{
        return new GModServer(xf.id, xf.name, xf.ip, xf.port, xf.map, xf.gamemode, xf.online, xf.max_online);
    }
}