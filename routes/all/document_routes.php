<?php

use App\Http\Controllers\DocumentController;

use Illuminate\Support\Facades\Route;


Route::resource('document', DocumentController::class);
Route::post('restore-document/{id}', [DocumentController::class, 'restore'])->name('document.restore');
Route::delete('force-delete-document/{id}', [DocumentController::class, 'forceDelete'])->name('document.forceDelete');
Route::post('document-status/{value}/{id}', [DocumentController::class, 'document.changeStatus']);
Route::post('document/index', [DocumentController::class, 'filterDocument'])->name('document.filter');
Route::post('insert_file', [DocumentController::class, 'InsertFile']);
Route::post('editFile', [DocumentController::class, 'editSelectedFile']);
Route::delete('deleteFile/{name}/{id}', [DocumentController::class, 'deleteSelectedFile']);
