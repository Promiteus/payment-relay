<?php

use App\Http\Controllers\ProductController;
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


Route::prefix('qiwi')->group(function() {
       Route::post('/bill', PaymentController::class.'@create')->name('create.bill');
       Route::post('/cancel/{billId}', PaymentController::class.'@cancel')->name('cancel.bill');
       Route::get('/info/{billId}', PaymentController::class.'@info')->name('status.bill');
       Route::post('bill/status', PaymentController::class.'@notify')->name('notify.bill');
});



Route::get('products', ProductController::class.'@getPageable')->name('get.products.page');
Route::get('products/category/{category}', ProductController::class.'@getPageableByCategory')->name('get.products.category.page');

