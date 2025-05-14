<?php

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

Route::resource('country', CountryController::class);
Route::post('restore-country/{id}', [CountryController::class, 'restore'])->name('country.restore');
Route::delete('force-delete-unit/{id}', [CountryController::class, 'forceDelete'])->name('country.forceDelete');
Route::post('country-status/{id}', [CountryController::class, 'changeStatus'])->name('country.status');



