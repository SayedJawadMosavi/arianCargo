<?php

use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::resource('unit', UnitController::class);
Route::post('restore-unit/{id}', [UnitController::class, 'restore'])->name('unit.restore');
Route::delete('force-delete-unit/{id}', [UnitController::class, 'forceDelete'])->name('unit.forceDelete');
Route::post('unit-status/{id}', [UnitController::class, 'changeStatus'])->name('unit.status');



