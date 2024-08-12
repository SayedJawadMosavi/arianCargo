<?php

use App\Http\Controllers\ExpenseCategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('expense_category', ExpenseCategoryController::class);
Route::post('restore-category/{id}', [ExpenseCategoryController::class, 'restore'])->name('category.restore');
Route::delete('force-delete-category/{id}', [ExpenseCategoryController::class, 'forceDelete'])->name('category.forceDelete');
Route::post('category-status/{value}/{id}', [ExpenseCategoryController::class, 'category.changeStatus']);



