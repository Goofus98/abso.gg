<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\GmodBans;
class GmodBansController extends Controller
{
    
    public function addBan(Request $request)
    {

        $data = $this->validate($request, [
            'SteamID' => "required|max:32",
            'Reason' => 'nullable|max:255',
            'ExpiryDate' => 'required|integer|min:0|max:2147483647'
        ]);
        $data['Reason'] = $data['Reason'] ?? "";

        $ban = GmodBans::create($request->only(['SteamID', 'Reason', 'ExpiryDate']) + ['Type' => 'ban']);

        return compact('ban');
    }
}
