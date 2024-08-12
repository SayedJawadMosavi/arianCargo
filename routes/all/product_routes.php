<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('product/min_stock', [ProductController::class, 'minStock'])->name('product.min_stock');
Route::get('product/{id}/stock', [ProductController::class, 'productStock'])->name('product.stock');
Route::get('product/{id}/log', [ProductController::class, 'productLog'])->name('product.log');
Route::resource('product', ProductController::class);
Route::post('restore-product/{id}', [ProductController::class, 'restore'])->name('product.restore');
Route::delete('force-delete-product/{id}', [ProductController::class, 'forceDelete'])->name('product.forceDelete');

Route::get('category-list', [ProductController::class, 'product.getCategory']);
Route::post('product-status/{value}/{id}', [ProductController::class, 'product.changeStatus']);



