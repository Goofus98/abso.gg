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

export interface CommunityStatTransfer {
    player_count: number;
    play_time: number;
    discord_online_user_count: number;
}

export class CommunityStat {
    constructor(
        public player_count:number,
        public play_time:number,
        public discord_online_user_count:number
    ){}

    static hydrate(xf: CommunityStatTransfer): CommunityStat{
        return new CommunityStat(xf.player_count, xf.play_time, xf.discord_online_user_count);
    }
}


export interface GModBansTransfer {
    id: number;
    SteamID: string;
    Reason: string;
    Type: string;
    Admin: string;
    ExpiryDate: number;
    Revoked: number;
    Revoker: string;
    RevokeReason: string;
    revoked_at: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    banned_user_avatar: string;
    admin_user_avatar: string;
    banned_user_avatar_frame: string;
    admin_user_avatar_frame: string;

    banned_user_name: string;
    admin_name: string;
}

export class GModBans {
    constructor(
        public id: number,
        public SteamID: string,
        public Reason: string,
        public Type: string,
        public Admin: string,
        public ExpiryDate: number,
        public Revoked: number,
        public Revoker: string,
        public RevokeReason: string,
        public revoked_at: string,
        public created_at: string,
        public updated_at: string,
        public deleted_at: string,
        public banned_user_avatar: string,
        public admin_user_avatar: string,
        public banned_user_avatar_frame: string,
        public admin_user_avatar_frame: string,
        public banned_user_name: string,
        public admin_name: string
    ){
        this.created_at = new Intl.DateTimeFormat('en-US', {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }).format(new Date(this.created_at));
    }

    static hydrate(xf: GModBansTransfer): GModBans{
        return new GModBans(xf.id, xf.SteamID, xf.Reason, xf.Type, xf.Admin, xf.ExpiryDate, xf.Revoked, xf.Revoker, xf.RevokeReason, xf.revoked_at, xf.created_at, xf.updated_at, xf.deleted_at, xf.banned_user_avatar, xf.admin_user_avatar, xf.banned_user_avatar_frame, xf.admin_user_avatar_frame,  xf.banned_user_name, xf.admin_name);
    }
}

export interface PaginationLinksTransfer {
    url: string;
    label: string;
    active: boolean;
}

export class PaginationLinks {
    constructor(
        public url: string,
        public label: string,
        public active: boolean
    ){}

    static hydrate(xf: PaginationLinksTransfer): PaginationLinks{
        return new PaginationLinks(xf.url, xf.label, xf.active);
    }
}



