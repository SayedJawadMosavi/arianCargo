<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\branchTransactionController;
use Illuminate\Support\Facades\Route;


Route::resource('branch', BranchController::class);
Route::post('restore-branch/{id}', [BranchController::class, 'restore'])->name('branch.restore');
Route::delete('force-delete-branch/{id}', [BranchController::class, 'forceDelete'])->name('branch.forceDelete');
Route::post('branch-status/{value}/{id}', [BranchController::class, 'branch.changeStatus']);
