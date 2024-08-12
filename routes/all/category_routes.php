<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('category', CategoryController::class);
Route::post('restore-category/{id}', [CategoryController::class, 'restore'])->name('category.restore');
Route::delete('force-delete-category/{id}', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');
Route::post('category-status/{value}/{id}', [CategoryController::class, 'category.changeStatus']);



