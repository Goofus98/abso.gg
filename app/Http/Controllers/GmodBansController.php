<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\GmodBans;
use App\Models\User;
class GmodBansController extends Controller
{
    public function getBans(Request $request)
    {
        $perPage = $request->input('items', 10);

        //if ($request->filled('search')) {
        return GmodBans::search($request->search)
            ->query(function ($query) {
                $query
                    // Join for banned user
                    ->join('users as banned_user', 'gmod_bans.SteamID', '=', 'banned_user.steam_id')

                    // Join for admin user
                    ->leftJoin('users as admin_user', 'gmod_bans.Admin', '=', 'admin_user.steam_id')

                    ->select([
                        'gmod_bans.id',
                        'gmod_bans.SteamID',
                        'gmod_bans.Reason',
                        'gmod_bans.Type',
                        'gmod_bans.Admin',
                        'gmod_bans.ExpiryDate',
                        'gmod_bans.Revoked',
                        'gmod_bans.Revoker',
                        'gmod_bans.RevokeReason',
                        'gmod_bans.revoked_at',
                        'gmod_bans.created_at',
                        'gmod_bans.updated_at',
                        'gmod_bans.deleted_at',
                        // Aliased user names
                        'banned_user.avatar_url as banned_user_avatar',
                        'admin_user.avatar_url as admin_user_avatar',
                        'banned_user.avatar_frame as banned_user_avatar_frame',
                        'admin_user.avatar_frame as admin_user_avatar_frame',
                        'banned_user.name as banned_user_name',
                        'admin_user.name as admin_name',
                    ])
                    ->orderBy('gmod_bans.created_at', 'desc');
            })
            ->paginate($perPage);
        // }

        //return GmodBans::orderBy('created_at', 'desc')
        //->paginate($perPage);
    }
    public function addBan(Request $request)
    {
        $data = $this->validate($request, [
            'SteamID' => "required|max:32",
            'Reason' => 'nullable|max:255',
            'ExpiryDate' => 'required|integer|min:0|max:2147483647'
        ]);
        $data['Reason'] = $data['Reason'] ?? "";

        User::firstOrCreate(['steam_id' => $request->SteamID]);
        $ban = GmodBans::create($request->only(['SteamID', 'Reason', 'ExpiryDate']) + ['Type' => 'ban']);

        return compact('ban');
    }

    public function changeReason(Request $request)
    {
        $article = GmodBans::first();
        $article->Reason = $request->Reason;
        $article->save();
    }
}
