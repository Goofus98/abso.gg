<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\GmodBans;
use App\Models\User;
class GmodBansController extends Controller
{
    public function getBans(){
        return GmodBans::all();
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
