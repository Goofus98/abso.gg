<?php

namespace App\Http\Controllers;

use App\Services\GPShopService;
use Illuminate\Http\Request;

class GPShopController extends Controller
{
    public function auction(Request $request, GPShopService $shopService)
    {
        
        $isBidding = $request->input('is_bidding');
        $uid = $request->input('uid');
        $itemId = $request->input('item_id');
        
        $price = $request->input('price');
        $duration = $request->input('duration');
        $purchaseData = $request->input('purchase_data');

        $encodedMods = $request->input('mods');
        $mods = base64_decode($encodedMods);
        $inventory = base64_decode($request->input('inventory'));

        $payload = $request->attributes->get('jwt_payload');
        $steamId = $payload->steamid;

        $auction = $shopService->createAuction(
            $steamId,
            $itemId,
            $uid,
            $price,
            $isBidding,
            $duration,
            $purchaseData,
            $mods,
            $inventory
        );

        $auction["Mods"] = $encodedMods;
        return response()->json([$auction]);
    }
}
