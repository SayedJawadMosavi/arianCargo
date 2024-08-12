<?php

use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorTransactionController;
use Illuminate\Support\Facades\Route;
Route::resource('vendors', VendorController::class);
Route::get('vendors/{vendor}/statement', [VendorController::class, 'statement'])->name('vendors.statement');
Route::post('vendors/{vendor}/statement', [VendorController::class, 'statement'])->name('vendors.statement');
Route::post('restore-vendors/{id}', [VendorController::class, 'restore'])->name('vendors.restore');
Route::delete('force-delete-vendors/{id}', [VendorController::class, 'forceDelete'])->name('vendors.forceDelete');
Route::post('vendor-status/{value}/{id}', [VendorController::class, 'changeStatus']);
Route::resource('vendor_transaction', VendorTransactionController::class);
Route::get('/getvendorCurrency/{data}', [VendorTransactionController::class, 'getVendorCurrency']);
Route::get('/findCurrency/{account_id}/{currency_id}', [VendorTransactionController::class, 'findCurrency']);
Route::post('vendors/statment', [VendorController::class, 'filterStatment'])->name('statement.filter');
