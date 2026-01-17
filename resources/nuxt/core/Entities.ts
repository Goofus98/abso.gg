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
    ReasonEdited: boolean;
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
        public ReasonEdited: boolean,
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
        return new GModBans(xf.id, xf.SteamID, xf.Reason, xf.ReasonEdited, xf.Type, xf.Admin, xf.ExpiryDate, xf.Revoked, xf.Revoker, xf.RevokeReason, xf.revoked_at, xf.created_at, xf.updated_at, xf.deleted_at, xf.banned_user_avatar, xf.admin_user_avatar, xf.banned_user_avatar_frame, xf.admin_user_avatar_frame,  xf.banned_user_name, xf.admin_name);
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

export interface GModBansAuditsValuesTransfer{
    id: number;
    Admin: string;
    SteamID: string;
    Reason: string;
    ExpiryDate: number;
    Type: string;
}

export class GModBansAuditsValues {
    constructor(
        public id: number,
        public Admin: string,
        public SteamID: string,
        public Reason: string,
        public ExpiryDate: number,
        public Type: string
    ){}

    static hydrate(xf: GModBansAuditsValuesTransfer): GModBansAuditsValues{
        return new GModBansAuditsValues(xf.id, xf.Admin, xf.SteamID, xf.Reason, xf.ExpiryDate, xf.Type);
    }
}

export interface GModBansAuditsTransfer {
    id: number;
    user_id: number;
    auditable_id: number;
    event: string;
    old_values: GModBansAuditsValuesTransfer;
    new_values: GModBansAuditsValuesTransfer;
    created_at: string;
    updated_at: string;
}

export class GModBansAudits {
    constructor(
        public id: number,
        public user_id: number,
        public auditable_id: number,
        public event: string,
        public old_values: GModBansAuditsValuesTransfer,
        public new_values: GModBansAuditsValuesTransfer,
        public created_at: string,
        public updated_at: string
    ){}

    static hydrate(xf: GModBansAuditsTransfer): GModBansAudits{
        return new GModBansAudits(xf.id, xf.user_id, xf.auditable_id, xf.event, new GModBansAuditsValues(xf.old_values.id, xf.old_values.Admin, xf.old_values.SteamID, xf.old_values.Reason, xf.old_values.ExpiryDate, xf.old_values.Type), new GModBansAuditsValues(xf.new_values.id, xf.new_values.Admin, xf.new_values.SteamID, xf.new_values.Reason, xf.new_values.ExpiryDate, xf.new_values.Type), xf.created_at, xf.updated_at);
    }
}

