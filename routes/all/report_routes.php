<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/report/expense', [ReportController::class, 'expenseReport'])->name('report.expense');
Route::post('/report/expense', [ReportController::class, 'getExpenseReport'])->name('report.expense.post');
Route::get('/report/income', [ReportController::class, 'incomeReport'])->name('report.income');
Route::post('/report/income', [ReportController::class, 'getIncomeReport'])->name('report.income.post');
Route::get('/report/sell', [ReportController::class, 'sellReport'])->name('report.sell');
Route::post('/report/sell', [ReportController::class, 'getSellReport'])->name('report.sell.post');
Route::get('/report/purchase', [ReportController::class, 'purchaseReport'])->name('report.purchase');
Route::post('/report/purchase', [ReportController::class, 'getPurchaseReport'])->name('report.purchase.post');
Route::get('/report/available_stock', [ReportController::class, 'AvailableReport'])->name('report.available_stock');
Route::post('/report/available_stock', [ReportController::class, 'getAvailableReport'])->name('report.available_stock.post');
Route::get('/report/main_stock', [ReportController::class, 'MainStockReport'])->name('report.main_stock_report');
Route::post('/report/main_stock', [ReportController::class, 'getMainStockReport'])->name('report.main_stock.post');
Route::get('/report/stock_transfer_report', [ReportController::class, 'StockTransferReport'])->name('report.stock_transfer_report');
Route::post('/report/stock_transfer_report', [ReportController::class, 'getStockTransferReport'])->name('report.stock_transfer.post');
Route::get('/report/main_transfer_report', [ReportController::class, 'MainTransferReport'])->name('report.main_transfer_report');
Route::post('/report/main_transfer_report', [ReportController::class, 'getMainTransferReport'])->name('report.main_transfer.post');
Route::get('/report/due_clients', [ReportController::class, 'DueClientReport'])->name('report.due_clients');
Route::post('/report/due_clients', [ReportController::class, 'getDueClientReport'])->name('report.due_clients.post');
Route::get('/report/due_vendor', [ReportController::class, 'DueVendorReport'])->name('report.due_vendor');
Route::post('/report/due_vendor', [ReportController::class, 'getDueVendorReport'])->name('report.due_vendor.post');
Route::get('/report/all_vailable_report', [ReportController::class, 'AllAvailableReport'])->name('report.all_vailable_report');
Route::post('/report/all_vailable_report', [ReportController::class, 'getAllAvailableReport'])->name('report.all_vailable_report.post');
Route::get('/report/profit_lost_report', [ReportController::class, 'PrfitLostReport'])->name('report.profit_lost_report');
Route::post('/report/profit_lost_report', [ReportController::class, 'getPrfitLostReport'])->name('report.profit_lost_report.post');

Route::get('/report/itemwise_sell', [ReportController::class, 'itemWiseSellReport'])->name('report.item_wise_sell_report');
Route::post('/report/itemwise_sell', [ReportController::class, 'getItemWiseSellReport'])->name('report.item_wise_sell_report.post');
Route::get('/report/itemwise_purchase', [ReportController::class, 'itemWisePurchaseReport'])->name('report.item_wise_purchase_report');
Route::post('/report/itemwise_purchase', [ReportController::class, 'getItemWisePurchaseReport'])->name('report.item_wise_purchase_report.post');

