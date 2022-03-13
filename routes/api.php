<?php

use App\Http\Controllers\PromotionController;
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

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::group([
//                 'middleware' => 'auth:sanctum', // todo: uncomment this line for user authentication
                 'prefix' => 'voucher',
             ], function() {
    Route::post('/check', [PromotionController::class, 'eligibleCheck']);
    Route::post('/claim', [PromotionController::class, 'claim']);
});

