<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("areas", "GmodServersController@retrieve");

Route::get("servers", "GmodServersController@retrieve");

Route::get("communitystats", "CommunityStatsController@retrieve");

Route::post("ban", "GmodBansController@addBan");


Route::prefix('garrysmod')->middleware(['gmod.auth', 'jwt.auth'])->group(function () {
    Route::post('gpshop/auction-item', "GPShopController@auction");
});

Route::get('/auth/player-token', "JwtAuthController@issue");//->middleware('gmod.auth');
Route::get('/auth/player-token-status', "JwtAuthController@status")->middleware('gmod.auth');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
