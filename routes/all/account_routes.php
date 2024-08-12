<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('account/{account}/statement', [AccountController::class, 'statement'])->name('account.statement');
Route::post('account/{account}/statement', [AccountController::class, 'getStatement'])->name('account.statement.filter');

Route::resource('account', AccountController::class);
Route::post('restore-account/{id}', [AccountController::class, 'restore'])->name('account.restore');
Route::delete('force-delete-account/{id}', [AccountController::class, 'forceDelete'])->name('account.forceDelete');
Route::post('account-status/{id}', [AccountController::class, 'changeStatus'])->name('account.status');
