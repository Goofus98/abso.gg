<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Throwable;
//use App\Models\AuctionItem;

class GPShopService
{
    public function createAuction(
        string $steamId,
        int $itemId,
        int $uid,
        int $price,
        bool $isBidding,
        int $duration,
        string $purchaseData,     // assumed to be JSON string
        string $mods,             // binary (base64 or raw string)
        string $inventory         // binary (base64 or raw string)
    )
    {
        $data  = [];
        $auctionItem = null;

        $connection = DB::connection('garrysmod_sql');

        try {
            $connection->beginTransaction();

            $connection->table('gp_shop_data')->updateOrInsert(
                ['SteamID' => $steamId],
                ['Inventory' => $inventory] // binary data
            );

            $auctionId = $connection->table('gp_shop_auctions')->insertGetId([
                'SteamID'        => $steamId,
                'IsBidding'      => $isBidding,
                'ItemID'         => $itemId,
                'UID'            => $uid,
                'StartingBid'    => $isBidding ? $price : 0,
                'CurrentBid'     => 0,
                'BuyoutPrice'    => !$isBidding ? $price : 0,
                'BidderSteamID'  => "",
                'Duration'       => $duration,
                'PurchaseData'   => $purchaseData, // JSON string
                'Mods'           => $mods,          // binary or base64
                'created_at'     => now(),
            ]);

            $row = $connection->table('gp_shop_auctions')->where('AuctionID', $auctionId)->first();


            $connection->commit();
    
            if ($row) {
                $isBidding = (bool) $row->IsBidding;

                $GPprice = $isBidding
                    ? ($row->CurrentBid ?: $row->StartingBid)
                    : $row->BuyoutPrice;

                $auctionItem = [
                    'AuctionID'      => $row->AuctionID,
                    'SteamID'        => $row->SteamID,
                    'IsBidding'      => $isBidding,
                    'ItemID'         => (string) $row->ItemID,
                    'UID'            => (string) $row->UID,
                    'Price'          => ['GP' => $GPprice],
                    'BidderSteamID'  => $row->BidderSteamID,
                    'Duration'       => $row->Duration,
                    'PurchaseData'   => $row->PurchaseData,
                    'Mods'           => $row->Mods, // You define this helper
                    'Time'           => $row->created_at,
                ];

                return $auctionItem;
            }

        } catch (\Exception $e) {
            $connection->rollBack();
            // Log or handle the error
            throw $e;
        }

        return null; 
    }


}