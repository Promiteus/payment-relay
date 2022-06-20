<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Qiwi\PaymentController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(function() {
   Route::prefix('qiwi')->group(function() {
       Route::post('/bill', PaymentController::class.'@create');
       Route::post('/cancel/{billId}', PaymentController::class.'@cancel');
       Route::get('/info/{billId}', PaymentController::class.'@info');
   });

   Route::post('bill/status', PaymentController::class.'@notify');
});
