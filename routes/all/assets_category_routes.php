<?php

use App\Http\Controllers\AssetsCategoryController;

use Illuminate\Support\Facades\Route;


Route::resource('asset_category', AssetsCategoryController::class);
Route::post('restore-asset_category/{id}', [AssetsCategoryController::class, 'restore'])->name('asset.restore');
Route::delete('force-delete-asset_category/{id}', [AssetsCategoryController::class, 'forceDelete'])->name('asset.forceDelete');
Route::post('asset-status/{value}/{id}', [AssetsCategoryController::class, 'asset.changeStatus']);
