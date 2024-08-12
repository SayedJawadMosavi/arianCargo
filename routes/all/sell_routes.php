<?php

use App\Http\Controllers\RateController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

Route::get('sell/{id}/bill', [SellController::class, 'bill'])->name('sell.bill');
Route::post('sell/index', [SellController::class, 'filterSell'])->name('sell.filter');
Route::get('sell/index', [SellController::class, 'index']);
Route::resource('sell', SellController::class);
Route::get('sell/{id}/detail', [SellController::class, 'getsellDetail'])->name('sell.detail.get');
Route::delete('sell-detail/{id}/delete', [SellController::class, 'sellDetailDelete'])->name('sell.detail.delete');
Route::post('sell-detail/update', [SellController::class, 'sellDetailUpdate'])->name('sell.detail.update');
Route::post('sell-detail/{sell}/insert', [SellController::class, 'sellDetailInsert'])->name('sell.detail.insert');
Route::post('restore-sell/{id}', [SellController::class, 'restore'])->name('sell.restore');
Route::delete('force-delete-sell/{id}', [SellController::class, 'forceDelete'])->name('sell.forceDelete');

Route::get('/get-products/{id}', [SellController::class, 'getProducts']);
Route::get('/get-client-currency/{id}', [SellController::class, 'getClientCurrencyId']);
Route::get('/get-client-data/{id}', [SellController::class, 'getClientCurrencyData']);
Route::get('/get-product-currency/{id}', [SellController::class, 'getProductCurrencyId']);

Route::get('get_latest_exchange_rate/{account_id}', [RateController::class, 'get_latest_rate']);

