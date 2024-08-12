<?php

use App\Http\Controllers\SellController;
use App\Http\Controllers\SellReturnController;
use Illuminate\Support\Facades\Route;

Route::resource('sellreturn', SellReturnController::class);
Route::get('sellreturn/{id}/detail', [SellReturnController::class, 'getSellReturnDetail'])->name('sellreturn.detail.get');
Route::delete('sellreturn-detail/{id}/delete', [SellReturnController::class, 'sellReturnDetailDelete'])->name('sellreturn.detail.delete');
Route::post('sellreturn-detail/update', [SellReturnController::class, 'sellReturnDetailUpdate'])->name('sellreturn.detail.update');
Route::post('sellreturn-detail/{sell}/insert', [SellReturnController::class, 'sellReturnDetailInsert'])->name('sellreturn.detail.insert');
Route::post('restore-sell/{id}', [SellReturnController::class, 'restore'])->name('sellreturn.restore');
Route::delete('force-delete-sell/{id}', [SellReturnController::class, 'forceDelete'])->name('sellreturn.forceDelete');
Route::post('sellreturn/index', [SellReturnController::class, 'filterSellReturn'])->name('sellreturn.filter');

Route::get('/select_data/{data}', [SellReturnController::class, 'getData']);

