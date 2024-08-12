<?php

use App\Http\Controllers\DocumentCategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('document_category', DocumentCategoryController::class);
Route::post('restore-document_category/{id}', [DocumentCategoryController::class, 'restore'])->name('document_category.restore');
Route::delete('force-delete-document_category/{id}', [DocumentCategoryController::class, 'forceDelete'])->name('document_category.forceDelete');
Route::post('document_category-status/{value}/{id}', [DocumentCategoryController::class, 'document_category.changeStatus']);



