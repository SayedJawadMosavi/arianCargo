<?php

use App\Http\Controllers\AccountTransferController;
use App\Http\Controllers\RateController;
use Illuminate\Support\Facades\Route;

Route::resource('account_transfer', AccountTransferController::class);
// Route::get('account_transfer/{account}/statement', [AccountTransferController::class, 'statement'])->name('account.statement');
Route::post('restore-account-transaction/{id}', [AccountTransferController::class, 'restore'])->name('account_transfer.restore');
Route::delete('force-delete-account-transaction/{id}', [AccountTransferController::class, 'forceDelete'])->name('account_transfer.forceDelete');
Route::get('/getAccounts/{data}', [AccountTransferController::class, 'getAccounts']);
Route::get('/getOtherAccounts/{data}', [AccountTransferController::class, 'getOtherAccounts']);
Route::get('getFromToRates/{from}/{to}', [RateController::class, 'getLatestRateFromToAccounts']);

