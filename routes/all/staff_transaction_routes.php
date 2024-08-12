<?php

use App\Http\Controllers\StaffDepositWithdrawController;
use App\Http\Controllers\StaffSalaryController;
use Illuminate\Support\Facades\Route;

Route::resource('staff_transaction', StaffDepositWithdrawController::class);
Route::resource('staff_salary', StaffSalaryController::class);

Route::get('/getstaffloan/{data}', [StaffSalaryController::class, 'getSfaffLoan']);


