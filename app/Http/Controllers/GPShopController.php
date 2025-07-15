<?php

namespace App\Http\Controllers;

use App\Services\GPShopService;
use Illuminate\Http\Request;

class GPShopController extends Controller
{
    public function auction(Request $request, GPShopService $shopService)
    {
        
        $payload = $request->attributes->get('jwt_payload');
        $steamId = $payload->steamid;

        $isBidding = (bool) $request->input('is_bidding');
        $uid = (int) $request->input('uid');
        $itemId = (int) $request->input('item_id');
        
        
        $price = (int) $request->input('price');
        $duration = (int) $request->input('duration');
        $purchaseData = $request->input('purchase_data');
        $mods = $request->input('mods');
        $inventory = $request->input('inventory');
        

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

        return response()->json(['auction' => $auction]);
    }
}
