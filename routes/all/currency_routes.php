<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::resource('currency', CurrencyController::class);
Route::post('restore-currency/{id}', [CurrencyController::class, 'restore'])->name('currency.restore');
Route::delete('force-delete-currency/{id}', [CurrencyController::class, 'forceDelete'])->name('currency.forceDelete');
Route::post('currency-status/{id}', [CurrencyController::class, 'changeStatus'])->name('currency.status');
