<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::resource('expense', ExpenseController::class);
Route::post('restore-expense/{id}', [ExpenseController::class, 'restore'])->name('expense.restore');
Route::delete('force-delete-expense/{id}', [ExpenseController::class, 'forceDelete'])->name('expense.forceDelete');
Route::get('/expense-category/{data}', [ExpenseController::class, 'getData']);
Route::post('expense/index', [ExpenseController::class, 'filterExpense'])->name('expense.filter');
Route::post('income/index', [ExpenseController::class, 'filterIncome'])->name('income.filter');
Route::get('/get-currency/{id}', [ExpenseController::class, 'getCurrencyData']);
Route::get('/showincomeindex', [ExpenseController::class, 'getIncomeData'])->name('income.index');




