<?php

use App\Http\Controllers\AssetsController;

use Illuminate\Support\Facades\Route;


Route::resource('asset', AssetsController::class);
Route::post('restore-asset/{id}', [AssetsController::class, 'restore'])->name('asset.restore');
Route::delete('force-delete-asset/{id}', [AssetsController::class, 'forceDelete'])->name('asset.forceDelete');
Route::post('asset-status/{value}/{id}', [AssetsController::class, 'asset.changeStatus']);
