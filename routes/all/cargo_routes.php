<?php

use App\Http\Controllers\RateController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\CargoPaymentController;
use Illuminate\Support\Facades\Route;
Route::get('/cargo/reload', [CargoController::class, 'reloadClient'])->name('cargo.index_reload');

Route::get('cargo/{id}/bill', [CargoController::class, 'bill'])->name('cargo.bill');
Route::post('cargo/index', [CargoController::class, 'filterCargo'])->name('cargo.filter');
Route::get('cargo/index', [CargoController::class, 'index']);
Route::resource('cargo', CargoController::class);
Route::resource('cargo_payment', CargoPaymentController::class);
Route::get('cargo/{id}/detail', [CargoController::class, 'getcargoDetail'])->name('cargo.detail.get');
Route::delete('cargo-detail/{id}/delete', [CargoController::class, 'cargoDetailDelete'])->name('cargo.detail.delete');
Route::post('cargo-payment/update', [CargoPaymentController::class, 'update'])->name('cargo.payment.update');
Route::post('cargo-detail/update', [CargoController::class, 'cargoDetailUpdate'])->name('cargo.detail.update');
Route::post('cargo-detail/{cargo}/insert', [CargoController::class, 'cargoDetailInsert'])->name('cargo.detail.insert');
Route::post('restore-cargo/{id}', [CargoController::class, 'restore'])->name('cargo.restore');
Route::delete('force-delete-cargo/{id}', [CargoController::class, 'forceDelete'])->name('cargo.forceDelete');

Route::get('/get-products/{id}', [CargoController::class, 'getProducts']);
Route::get('/get-client-currency/{id}', [CargoController::class, 'getClientCurrencyId']);
Route::get('/get-client-data/{id}', [CargoController::class, 'getClientCurrencyData']);
Route::get('/get-product-currency/{id}', [CargoController::class, 'getProductCurrencyId']);
Route::post('/cargo/{id}/pay', [CargoController::class, 'pay'])->name('cargo.pay');

Route::get('get_latest_exchange_rate/{account_id}', [RateController::class, 'get_latest_rate']);

Route::get('/cargo-payment/{id}/edit', [CargoPaymentController::class, 'edit'])->name('cargo.payment.edit');
Route::delete('/cargo-payment/{id}', [CargoPaymentController::class, 'destroy'])->name('cargo.payment.destroy');
Route::get('branch/receivable', [CargoController::class, 'branchReceivable'])->name('branch.receivable');
Route::post('/branch/receivable', [CargoController::class, 'getBranchReceivableReport'])->name('report.branch_receivable.post');
Route::get('/get-country-rates/{country}', [CargoController::class, 'getCountryRates']);
