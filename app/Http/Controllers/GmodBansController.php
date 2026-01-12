<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\GmodBans;
use App\Models\User;
class GmodBansController extends Controller
{
    public function getBans(Request $request){
        $query = GmodBans::query();
        $page = GmodBans::paginate(10);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('SteamID', 'like', '%' . $request->search . '%')
                ->orWhere('Reason', 'like', '%' . $request->search . '%')
                ->orWhere('Admin', 'like', '%' . $request->search . '%');
            });
        }
        /*return [
            'current_page' => $page->currentPage(),
            'data' => $page->items(),
            'from' => $page->firstItem(),
            'last_page' => $page->lastPage(),
            'file_size' => $this->fileSize,
            'created_at' => $this->created_at,
            'company' => new CompanyResource($this->company),
        ];*/

        return $query->latest()->pagination(10);
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
