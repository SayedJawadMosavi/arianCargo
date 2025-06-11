<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Cargo;
use App\Models\CargoPayment;
use App\Models\ClientCurrency;
use App\Models\ClientLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargoPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CargoPayment  $cargoPayment
     * @return \Illuminate\Http\Response
     */
    public function show(CargoPayment $cargoPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CargoPayment  $cargoPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(CargoPayment $cargoPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CargoPayment  $cargoPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CargoPayment $cargoPayment)
    {
        DB::beginTransaction();
        try {

            $payment = CargoPayment::find($request->id);
            $cargo = Cargo::findOrFail($payment->cargo_id);
            $client_currency = ClientCurrency::find($payment->client_currency_id);

            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            $branch_base = $base_currency->currency_id; //
            Account::find($payment->account_id)->decrement('amount', $payment->amount);
            ClientCurrency::find($client_currency->id)->decrement('amount', $payment->amount);

            if ($branch_base == $cargo->currency_id) {

                $cargo->decrement('paid', $payment->amount);
                $cargo->increment('new_balance', $payment->amount);
            } else {
                $cargo->decrement('paid_equalent', $payment->amount);
                $cargo->increment('equalent_balance', $payment->amount);
            }

            Account::find($payment->account_id)->increment('amount', $request->amount);
            ClientCurrency::find($client_currency->id)->increment('amount', $request->amount);
            if ($branch_base == $cargo->currency_id) {
                $cargo->increment('paid', $request->amount);
                $cargo->decrement('new_balance', $request->amount);
            } else {
                $cargo->increment('paid_equalent', $request->amount);
                $cargo->decrement('equalent_balance', $request->amount);
            }
            $account =  Account::find($payment->account_id);

            $log = AccountLog::where(['action_id' => $payment->id, 'action' => 'cargo_payment'])->update(['amount'  => $request->amount, 'type'  => $payment->type, 'balance'  => $account->amount]);

            $client_log = ClientLog::where(['action_id' => $payment->id, 'action' => 'cargo_payment'])->update(['amount'  => $request->amount, 'type'  => $payment->type, 'available'  => $client_currency->amount]);
            $payment->update(['amount' => $request->amount, 'description' => $request->description]);


            DB::commit();
            return  response()->json(['success', ' updating Account Payment']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating Account Payment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CargoPayment  $cargoPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CargoPayment $cargoPayment)
    {

        try {
            DB::beginTransaction();
            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            $branch_base = $base_currency->currency_id; //
            $account = Account::find($cargoPayment->account_id);
            $cargo = Cargo::find($cargoPayment->cargo_id);
            if ($cargoPayment->type == "deposit") {
                Account::find($cargoPayment->account_id)->decrement('amount', $cargoPayment->amount);
                ClientCurrency::find($cargoPayment->client_currency_id)->decrement('amount', $cargoPayment->amount);
            }
            if ($branch_base == $account->currency_id) {
                $cargo->decrement('paid', $cargoPayment->amount);
                $cargo->increment('new_balance', $cargoPayment->amount);
            }else{

                $cargo->decrement('paid_equalent', $cargoPayment->amount);
                $cargo->increment('equalent_balance', $cargoPayment->amount);
            }
            AccountLog::where([
                'action'    => 'cargo_payment',
                'action_id'   => $cargoPayment->id,
            ])->forceDelete();

            ClientLog::where([
                'action'    => 'cargo_payment',
                'action_id'   => $cargoPayment->id,
            ])->delete();

            $cargoPayment->forceDelete();
            DB::commit();

            return redirect()->back()->with('success', 'Client Payment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }
}
