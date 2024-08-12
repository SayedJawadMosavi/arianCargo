<?php

use App\Http\Controllers\AccountTransactionController;
use Illuminate\Support\Facades\Route;

Route::resource('account_transaction', AccountTransactionController::class);
Route::post('restore-account_transaction/{id}', [AccountTransactionController::class, 'restore'])->name('account_transaction.restore');
Route::delete('force-delete-account_transaction/{id}', [AccountTransactionController::class, 'forceDelete'])->name('account_transaction.forceDelete');
