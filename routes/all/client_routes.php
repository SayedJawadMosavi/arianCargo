<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('client/{client}/statement', [ClientController::class, 'statement'])->name('client.statement');
Route::get('client/receivable', [ClientController::class, 'clientReceivable'])->name('client.receivable');
Route::get('client/payable', [ClientController::class, 'clientPayable'])->name('client.payable');
Route::resource('client', ClientController::class);
Route::resource('client_transaction', ClientTransactionController::class);
Route::post('restore-client/{id}', [ClientController::class, 'restore'])->name('client.restore');
Route::delete('force-delete-client/{id}', [ClientController::class, 'forceDelete'])->name('client.forceDelete');
Route::post('client-status/{value}/{id}', [ClientController::class, 'client.changeStatus']);
Route::get('/getClientCurrency/{data}', [ClientTransactionController::class, 'getClientCurrency']);
Route::get('/findCurrency/{account_id}/{currency_id}', [ClientTransactionController::class, 'findCurrency']);
Route::get('client/{id}/detail', [ClientController::class, 'getsellDetail'])->name('client.detail.get');
Route::post('client/statment', [ClientController::class, 'filterStatment'])->name('clientstatement.filter');
Route::post('client-client_clearance', [ClientController::class, 'clientClearanceSstore'])->name('client_clearance.store');
