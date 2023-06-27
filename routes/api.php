<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorksAPIController;
use App\Http\Controllers\ProxyOAIPMHController;
use App\Http\Controllers\ProxyEIDRController;
use App\Http\Controllers\HarvestOAIPMHController;
use App\Http\Controllers\Z3950Controller;
use App\Http\Controllers\CutterSanbornController;
use App\Http\Controllers\MARCQAController;

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

Route::get('eidr/{eidr}', [ProxyEIDRController::class, 'getEIDR']);


Route::get('z3950', [Z3950Controller::class, 'searchZ3950']);

Route::get('cutter', [CutterSanbornController::class, 'cutter']);

Route::post('marcqa', [MARCQAController::class, 'marcQA']);
Route::post('marcqaexportfield', [MARCQAController::class, 'exportfield']);