<?php

use App\Http\Controllers\StockProductController;
use Illuminate\Support\Facades\Route;

Route::get('stock_product/{stock_product}/statement', [StockProductController::class, 'statement'])->name('stock_product.statement');
Route::resource('stock_product', StockProductController::class);
Route::post('restore-stock_product/{id}', [StockProductController::class, 'restore'])->name('stock_product.restore');
Route::delete('force-delete-stock_product/{id}', [StockProductController::class, 'forceDelete'])->name('stock_product.forceDelete');
Route::post('stock_product-status/{value}/{id}', [StockProductController::class, 'stock_product.changeStatus']);
