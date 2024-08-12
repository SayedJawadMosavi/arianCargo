<?php

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('stock/{id}/substock', [StockController::class, 'subStockProducts'])->name('stock.sub.products');
Route::get('stock/{stock}/products', [StockController::class, 'products'])->name('stock.products');
Route::get('stock/products-list', [StockController::class, 'productsList'])->name('stock.productsList');
Route::get('stock/main-stock', [StockController::class, 'MainsList'])->name('stock.main_stock');
Route::resource('stock', StockController::class);
Route::post('restore-stock/{id}', [StockController::class, 'restore'])->name('stock.restore');
Route::delete('force-delete-stock/{id}', [StockController::class, 'forceDelete'])->name('stock.forceDelete');
Route::post('stock-status/{value}/{id}', [StockController::class, 'stock.changeStatus']);
