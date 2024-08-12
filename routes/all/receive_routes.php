<?php

use App\Http\Controllers\ReceiveController;
use Illuminate\Support\Facades\Route;

Route::resource('received', ReceiveController::class);
Route::post('restore-received/{id}', [ReceiveController::class, 'restore'])->name('received.restore');
Route::delete('force-delete-received/{id}', [ReceiveController::class, 'forceDelete'])->name('received.forceDelete');
Route::get('/received-category/{data}', [ReceiveController::class, 'getData']);
Route::post('received/index', [ReceiveController::class, 'filterreceived'])->name('received.filter');




