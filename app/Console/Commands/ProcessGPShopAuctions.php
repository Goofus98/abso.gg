<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessGPShopAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpshop:process-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and delete expired auctions from Garry\'s Mod database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function generateUniqueItemID($auction) {
        $seed = $auction->BidderSteamID .
                $auction->SteamID .
                time() .
                $auction->AuctionID .
                random_int(1000, 9999);

        return crc32($seed);
    }

    public function generatePurchaseData($auction) {
        return json_encode([
            'Time' => time(), // Unix timestamp, same as os.time()
            'Currency' => 'GP',
            'Amount' => $auction->OriginalMarketPriceGP ?? 0
        ]);
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connection = DB::connection('garrysmod_sql');
        $expiredAuctions = $connection->table('gp_shop_auctions')
            ->whereRaw('created_at + INTERVAL Duration HOUR <= NOW()')
            ->get();

        if ($expiredAuctions->isEmpty()) {
            $this->info('No expired auctions to process.');
            return Command::SUCCESS;
        }

        foreach ($expiredAuctions as $auction) {
            //If it was a bidding auction and it had a leading winner
            if ($auction->IsBidding && $auction->BidderSteamID != "") {
                $gp = $connection->table('gp_shop_data')
                    ->where('SteamID', $auction->BidderSteamID)
                    ->value('GP');
                //If the winner has enough money
                if ($gp >= $auction->CurrentBid) {
                    $uid = $this->generateUniqueItemID($auction);
                    try {
                        $connection->beginTransaction();
                        //Subtract GP from the winner
                        $connection->table('gp_shop_data')
                            ->where('SteamID', $auction->BidderSteamID)
                            ->decrement('GP', $auction->CurrentBid);

                        //Insert item into winner's inventory
                        $connection->table('gp_shop_inv')
                            ->insert([
                                'SteamID' => $auction->BidderSteamID,
                                'UID' => $uid,
                                'ItemID' => $auction->ItemID,
                                'Type' => $auction->Type,
                                'Equipped' => 0,
                                'Mods' => $auction->Mods,
                                'PurchaseData' => $this->generatePurchaseData($auction),
                            ]);

                        //Add GP to the seller
                        $connection->table('gp_shop_data')
                            ->where('SteamID', $auction->SteamID)
                            ->increment('GP', $auction->CurrentBid);

                        $connection->table('gp_shop_completed_auctions')->insert([
                            'AuctionID' => $auction->AuctionID,
                            'SteamID' => $auction->SteamID,
                            'IsBidding' => $auction->IsBidding ? 1 : 0,
                            'WinnerSteamID' => $auction->BidderSteamID,
                            'ItemID' => $auction->ItemID,
                            'UID' => $auction->UID,
                            'NewUID' => $uid,
                            'OriginalMarketPriceGP'=> $auction->OriginalMarketPriceGP,
                            'Type' => $auction->Type,
                            'FinalPrice' => $auction->CurrentBid,
                            'Duration' => $auction->Duration,
                            'PurchaseData' => $auction->PurchaseData,
                            'Mods' => $auction->Mods,
                        ]);

                        $connection->commit();
                    } catch (Throwable $e) {
                        $connection->rollBack();
                        Log::error("Auction {$auction->AuctionID} failed: {$e->getMessage()}");
                        continue;
                    }
                } else { //winner did not have enough money
                    try {
                        $connection->beginTransaction();

                        //Return item back to seller inventory
                        $connection->table('gp_shop_inv')->insert([
                            'SteamID' => $auction->SteamID,
                            'UID' => $auction->UID,
                            'ItemID' => $auction->ItemID,
                            'Type' => $auction->Type,
                            'Equipped' => 0,
                            'Mods' => $auction->Mods,
                            'PurchaseData' => $auction->PurchaseData,
                        ]);

                        //Log as expired auction
                        $connection->table('gp_shop_expired_auctions')->insert([
                            'AuctionID' => $auction->AuctionID,
                            'SteamID' => $auction->SteamID,
                            'IsBidding' => $auction->IsBidding ? 1 : 0,
                            'ItemID' => $auction->ItemID,
                            'UID' => $auction->UID,
                            'Type' => $auction->Type,
                            'OriginalMarketPriceGP'=> $auction->OriginalMarketPriceGP,
                            'Price' => $auction->StartingBid,
                            'Duration' => $auction->Duration,
                            'PurchaseData' => $auction->PurchaseData,
                            'Mods' => $auction->Mods,
                        ]);

                        $connection->commit();
                    } catch (Throwable  $e) {
                        $connection->rollBack();
                        Log::error("Failed to return expired item from auction {$auction->AuctionID}: {$e->getMessage()}");
                        continue;
                    }

                }
            } else { //Is not a bidding auction (could be a buyout with no buyer)
                try {
                    $connection->beginTransaction();
                    //Return item back to seller inventory
                    $connection->table('gp_shop_inv')->insert([
                        'SteamID' => $auction->SteamID,
                        'UID' => $auction->UID,
                        'ItemID' => $auction->ItemID,
                        'Type' => $auction->Type,
                        'Equipped' => 0,
                        'Mods' => $auction->Mods,
                        'PurchaseData' => $auction->PurchaseData,
                    ]);
                    //Log as expired auction
                    $connection->table('gp_shop_expired_auctions')->insert([
                        'AuctionID' => $auction->AuctionID,
                        'SteamID' => $auction->SteamID,
                        'IsBidding' => $auction->IsBidding ? 1 : 0,
                        'ItemID' => $auction->ItemID,
                        'UID' => $auction->UID,
                        'Type' => $auction->Type,
                        'OriginalMarketPriceGP'=> $auction->OriginalMarketPriceGP,
                        'Price' => $auction->StartingBid,
                        'Duration' => $auction->Duration,
                        'PurchaseData' => $auction->PurchaseData,
                        'Mods' => $auction->Mods,
                    ]);

                    $connection->commit();
                } catch (Throwable $e) {
                    $connection->rollBack();
                    Log::error("Failed to expire auction {$auction->AuctionID}: {$e->getMessage()}");
                    continue;
                }
            }

            $connection->table('gp_shop_auctions')
                ->where('AuctionID', $auction->AuctionID)
                ->delete();
            // 👇 Optionally log, archive, or notify users here
            $this->info("Processed expired auction ID: {$auction->AuctionID}");
        }

        return Command::SUCCESS;
    }
}
