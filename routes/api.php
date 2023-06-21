<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorksAPIController;
use App\Http\Controllers\ProxyOAIPMHController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("works", WorksAPIController::class);

Route::prefix('oai')->group(
    function () {
        Route::get('identify', [ProxyOAIPMHController::class, 'getOAIPMHIdentify']);
        Route::get('listmetadataformats', [ProxyOAIPMHController::class, 'listMetadataFormats']);
        Route::get('listsets', [ProxyOAIPMHController::class, 'listSets']);
        Route::post('harvest', [HarvestOAIPMHController::class, 'harvest']);
        Route::get('list', [ProxyOAIPMHController::class, 'listOAIPMH']);
    }
);