<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\companyController;
use App\Http\Controllers\apiCallsController;
use App\Http\Controllers\SaleController;

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

Route::post('/login', [apiCallsController::class, 'userLogin']);
Route::post('/clientValidation', [companyController::class, 'clientValidation']);

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/ledger/{general_ledger_account_id}', [apiCallsController::class, 'ledger']);
    Route::get('/saleslist/{pageNo}/{limit}/{date}', [apiCallsController::class, 'saleslist']);
    Route::get('/purchaselist/{pageNo}/{limit}/{date}', [apiCallsController::class, 'purchaselist']);
    Route::get('/ledgerlist/{pageNo}/{limit}/{date}', [apiCallsController::class, 'ledgerList']);
    Route::get('/cashReceipts/{pageNo}/{limit}/{date}', [apiCallsController::class, 'cashReceipts']);
    Route::get('/getDashboardData', [apiCallsController::class, 'getDashboardData']);
    Route::get('/logout', [apiCallsController::class, 'logout']);
});
Route::post('/sendDataToLiveServer', [SaleController::class, 'sendDataToLiveServer']);
Route::post('/sendDataToLiveServerRefundInvoice', [SaleController::class, 'sendDataToLiveServerRefundInvoice']);