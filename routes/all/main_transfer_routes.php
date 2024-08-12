<?php

use App\Http\Controllers\MainTransferController;
use Illuminate\Support\Facades\Route;

Route::get('main_transfer/{main_transfer}/statement', [MainTransferController::class, 'statement'])->name('main_transfer.statement');
Route::get('main_transfer/single-transfer', [MainTransferController::class, 'singleTransfer'])->name('main_transfer.singleTransfer');
Route::get('main_transfer/{id}/detail', [MainTransferController::class, 'getMainTransferDetail'])->name('main_transfer.detail.get');

Route::resource('main_transfer', MainTransferController::class);
Route::post('restore-main_transfer/{id}', [MainTransferController::class, 'restore'])->name('main_transfer.restore');
Route::delete('force-delete-main_transfer/{id}', [MainTransferController::class, 'forceDelete'])->name('main_transfer.forceDelete');
Route::post('main_transfer-status/{value}/{id}', [MainTransferController::class, 'main_transfer.changeStatus']);
