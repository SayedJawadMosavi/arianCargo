<?php

use App\Http\Controllers\ShareHolderController;
use App\Http\Controllers\ShareholderTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('shareholder/{shareholder}/statement', [ShareHolderController::class, 'statement'])->name('shareholder.statement');
Route::post('shareholder/{shareholder}/statement', [ShareHolderController::class, 'getStatement'])->name('shareholder.statement.filter');
Route::resource('shareholder', ShareHolderController::class);
Route::post('restore-shareholder/{id}', [ShareHolderController::class, 'restore'])->name('shareholder.restore');
Route::delete('force-delete-shareholder/{id}', [ShareHolderController::class, 'forceDelete'])->name('shareholder.forceDelete');
Route::post('shareholder-status/{value}/{id}', [ShareHolderController::class, 'shareholder.changeStatus']);
Route::resource('shareholder_transaction', ShareholderTransactionController::class);
Route::get('/getshareholderCurrency/{data}', [ShareholderTransactionController::class, 'getShareholderCurrency']);
Route::get('/getshareholderCurrencyJson/{data}', [ShareholderTransactionController::class, 'getshareholderCurrencyJson']);
Route::get('/findCurrency/{account_id}/{currency_id}', [ShareholderTransactionController::class, 'findCurrency']);
