<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffDepositWithdrawController;
use Illuminate\Support\Facades\Route;

Route::resource('staff', StaffController::class);
Route::resource('staff_transaction', StaffDepositWithdrawController::class);
Route::get('get_documents/{id}', [StaffController::class, 'get_documents']);
Route::post('insertSelectedFile', [StaffController::class        , 'insertSelectedFile']);
Route::delete('deleteSelectedFile/{name}/{id}', [StaffController::class, 'deleteSelectedFile']);
Route::post('editSelectedFile', [StaffController::class, 'editSelectedFile']);
Route::get('staff/{staff}/statement', [StaffController::class, 'statement'])->name('staff.statement');
Route::put('staff_transaction/{staff_transaction}', [StaffDepositWithdrawController::class, 'update'])->name('staff_transaction.update');




