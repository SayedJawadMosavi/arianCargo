<?php

use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::resource('purchase', PurchaseController::class);
Route::post('purchase/index', [PurchaseController::class, 'filterPurchase'])->name('purchase.filter');
Route::get('purchase/index', [PurchaseController::class, 'index']);
Route::get('purchase/{id}/receive', [PurchaseController::class, 'getPurchaseReceive'])->name('purchase.receive.get');
Route::get('purchase/{id}/detail', [PurchaseController::class, 'getPurchaseDetail'])->name('purchase.detail.get');
Route::delete('purchase-detail/{id}/delete', [PurchaseController::class, 'purchaseDetailDelete'])->name('purchase.detail.delete');
Route::delete('purchase-receive/{id}/delete', [PurchaseController::class, 'purchaseReceiveDelete'])->name('purchase.receive.delete');
Route::post('purchase-detail/update', [PurchaseController::class, 'purchaseDetailUpdate'])->name('purchase.detail.update');
Route::post('purchase-receive/update', [PurchaseController::class, 'purchaseReceiveUpdate'])->name('purchase.receive.update');
Route::post('purchase-detail/{purchase}/insert', [PurchaseController::class, 'purchaseDetailInsert'])->name('purchase.detail.insert');
Route::post('restore-purchase/{id}', [PurchaseController::class, 'restore'])->name('purchase.restore');
Route::delete('force-delete-purchase/{id}', [PurchaseController::class, 'forceDelete'])->name('purchase.forceDelete');

Route::get('/get-product-remaining/{id}', [PurchaseController::class, 'getProductReceived']);

