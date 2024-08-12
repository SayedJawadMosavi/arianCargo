<?php

use App\Http\Controllers\StockTransferController;
use Illuminate\Support\Facades\Route;

Route::get('stock_transfer/{stock_transfer}/statement', [StockTransferController::class, 'statement'])->name('stock_transfer.statement');
Route::resource('stock_transfer', StockTransferController::class);
Route::post('restore-stock_transfer/{id}', [StockTransferController::class, 'restore'])->name('stock_transfer.restore');
Route::delete('force-delete-stock_transfer/{id}', [StockTransferController::class, 'forceDelete'])->name('stock_transfer.forceDelete');
Route::post('stock_transfer-status/{value}/{id}', [StockTransferController::class, 'stock_transfer.changeStatus']);
Route::get('/getStock/{data}', [StockTransferController::class, 'getStock']);
Route::get('/getStockProducts/{data}', [StockTransferController::class, 'getStockProducts']);
