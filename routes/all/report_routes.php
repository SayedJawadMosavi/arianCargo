<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/report/expense', [ReportController::class, 'expenseReport'])->name('report.expense');
Route::post('/report/expense', [ReportController::class, 'getExpenseReport'])->name('report.expense.post');
Route::get('/report/income', [ReportController::class, 'incomeReport'])->name('report.income');
Route::post('/report/income', [ReportController::class, 'getIncomeReport'])->name('report.income.post');
Route::get('/report/cargo', [ReportController::class, 'CargoReport'])->name('report.cargo');
Route::post('/report/cargo', [ReportController::class, 'getCargoReport'])->name('report.cargo.post');

Route::get('/report/due_clients', [ReportController::class, 'DueClientReport'])->name('report.due_clients');
Route::post('/report/due_clients', [ReportController::class, 'getDueClientReport'])->name('report.due_clients.post');

