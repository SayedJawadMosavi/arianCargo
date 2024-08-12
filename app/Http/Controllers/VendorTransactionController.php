<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Vendor;
use App\Models\VendorTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVendorTransactionRequest;
use App\Http\Requests\UpdateVendorTransactionRequest;
use App\Models\AccountLog;
use App\Models\VendorCurrency;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Http\Traits\VendorLogTrait;
use App\Http\Traits\CurrencyTrait;

use App\Models\Setting;
use App\Models\VendorLog;

class VendorTransactionController extends Controller
{
    use AccountLogTrait, VendorLogTrait, CurrencyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed = VendorTransaction::branch()->onlyTrashed()->get();
        $vendor_transactions = VendorTransaction::with('vendor_currency', 'vendor', 'account', 'vendor_currency.currency', 'account.currency')->get();

        return view('vendor_transaction.index', compact('vendor_transactions', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        $branch_base = $base_currency->currency_id; //
        $accounts = Account::branch()->where('currency_id', $branch_base)->orderBy('default', 'DESC')->get();
        $vendors = Vendor::branch()->get();

        return view('vendor_transaction.create', compact('accounts', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendorTransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $flag = false;
            $total = 0;
            $same = 'yes';
            $account = Account::find($request->account_id);
            // dd($request->all());
            if ($request->type == "withdraw") {
                if ($account->amount < $request->amount) {
                    throw new \Exception('Transaction can not be bigger than account');
                }
            }

            $amount = $request->amount;
            // $vendor_currency = VendorCurrency::where('currency_id',$account->currency_id)->first();
            $vendor_currency = $this->GetVendorCurrency($request->vendor_id, $account->currency_id);

            // if($account->currency_id == $vendor_currency->currency_id){
            // }else{
            //     $same = 'no';
            //     if($request->operation == 'divide'){
            //         $amount = $request->amount ;
            //         $total = $amount / $request->exchange_rate;
            //     }else{
            //         $amount = $request->amount ;
            //         $total = $amount * $request->exchange_rate;
            //     }

            // }
            // $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            // dd($vendor_currency);
            $vendor_transactions = VendorTransaction::create([

                'account_id' => $request->account_id,
                'vendor_id' => $request->vendor_id,
                'amount' => $request->amount,

                'description' => $request->description,
                'shamsi_date' => $request->shamsi_date,
                'rate' => $request->exchange_rate,
                'operation' => $request->operation,
                'same' => $same,
                'total' => $total,
                'miladi_date' => $request->miladi_date,
                'type' => $request->type,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);


            $type = ($request->type == "deposit") ? 'paid' : 'received';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            $flag = $account->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $amount);

            $flag = $this->InsertAccountLog($request->account_id, $request->type, $amount, $request->description, $account->amount, 'vendor_transaction', $vendor_transactions->id, $currentDate);

            // if($same == 'no'){
            //     $amount =  $total;
            // }

            $flag = $vendor_currency->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $amount);

            $this->InsertVendorLog($request->vendor_id, $vendor_currency->id, $type, $amount, $request->description, $vendor_currency->amount, 'vendor_transaction', $vendor_transactions->id, $currentDate);


            // ---------------------------------------------------------------------
            // if ($request->exchange_rate != null && $request->operation != null) {

            //     Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            //     VendorCurrency::find($request->vendor_account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->total);
            // } else {
            //     Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            //     VendorCurrency::find($request->vendor_account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            // }
            // $account = Account::find($request->account_id);
            // $flag = $this->InsertAccountLog($request->account_id, $request->type, $request->amount, $request->description, $account->amount, 'vendor_transaction', $vendor_transactions->id, $currentDate);
            // $this->InsertVendorLog($request->vendor_id, $request->vendor_account_id, $type, $request->amount, $request->description, $account->amount, 'vendor_transaction', $vendor_transactions->id,  $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('vendor_transaction.index')->with('success', 'vendor Transaction stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('vendor_transaction.index')->with('error', 'vendor Transaction Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating vendor Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorTransaction  $vendorTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(VendorTransaction $vendorTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorTransaction  $vendorTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorTransaction $vendorTransaction)
    {
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $vendors = Vendor::branch()->get();
        return view('vendor_transaction.create', compact('vendorTransaction', 'accounts', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorTransaction  $vendorTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorTransactionRequest $request, VendorTransaction $vendorTransaction)
    {
        DB::beginTransaction();
        try {
          $currency_id = Account::find($vendorTransaction->account_id);
          $vendor_request_currency = VendorCurrency::where('vendor_id', $request->vendor_id)->where('currency_id', $currency_id->currency_id)->first();
          $vendorTransaction_currency = VendorCurrency::where('vendor_id', $vendorTransaction->vendor_id)->where('currency_id', $currency_id->currency_id)->first();

            if ($vendorTransaction->exchange_rate == null && $vendorTransaction->operation == null) {
                if ($vendorTransaction->type == 'deposit') {
                    Account::find($vendorTransaction->account_id)->decrement('amount', $vendorTransaction->amount);
                    $vendorTransaction_currency->decrement('amount', $vendorTransaction->amount);
                } else {
                    Account::find($vendorTransaction->account_id)->increment('amount', $vendorTransaction->amount);
                   $vendorTransaction_currency->increment('amount', $vendorTransaction->amount);
                }
            } else {

                if ($vendorTransaction->type == 'deposit') {
                    Account::find($vendorTransaction->account_id)->decrement('amount', $vendorTransaction->amount);
                    $vendorTransaction_currency->decrement('amount', $vendorTransaction->total);
                } else {
                    Account::find($vendorTransaction->account_id)->increment('amount', $vendorTransaction->amount);
                   $vendorTransaction_currency->increment('amount', $vendorTransaction->total);
                }
            }
            $flag = $vendorTransaction->update([

                'account_id' => $request->account_id,
                'vendor_id' => $request->vendor_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => $request->type,
                'operation' => $request->operation,
                'rate' => $request->exchange_rate,
                'total' => $request->total,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'updated_by' => auth()->user()->id,
            ]);

            $type = ($request->type == "deposit") ? 'paid' : 'received';


            if ($request->exchange_rate != null && $request->operation != null) {
                Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
                $vendor_request_currency->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->total);
            } else {
                Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
                $vendor_request_currency->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            }
            $account=  Account::find($request->account_id);

            $log = AccountLog::where(['action_id' => $vendorTransaction->id, 'action' => 'vendor_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'balance'  =>$account->amount]);
            $vendor_log = VendorLog::where(['action_id' => $vendorTransaction->id, 'action' => 'vendor_transaction'])->update(['amount'  =>$request->amount,'type'  =>$type,'available'  =>$account->amount]);
            if ($flag) {
                DB::commit();
                return redirect()->route('vendor_transaction.index')->with('success', 'Vendor Transaction updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('vendor_transaction.index')->with('error', 'Vendor Transaction update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating Vendor Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorTransaction  $vendorTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorTransaction $vendorTransaction)
    {
        if ($vendorTransaction->exchange_rate == null && $vendorTransaction->operation == null) {
            if ($vendorTransaction->type == "deposit") {
                Account::find($vendorTransaction->account_id)->decrement('amount', $vendorTransaction->amount);
                VendorCurrency::find($vendorTransaction->vendor_currency_id)->decrement('amount', $vendorTransaction->amount);
            } else {
                Account::find($vendorTransaction->account_id)->increment('amount', $vendorTransaction->amount);
                VendorCurrency::find($vendorTransaction->vendor_currency_id)->increment('amount', $vendorTransaction->amount);
            }
        } else {
            if ($vendorTransaction->type == "deposit") {
                Account::find($vendorTransaction->account_id)->decrement('amount', $vendorTransaction->amount);
                VendorCurrency::find($vendorTransaction->vendor_currency_id)->decrement('amount', $vendorTransaction->total);
            } else {
                Account::find($vendorTransaction->account_id)->increment('amount', $vendorTransaction->amount);
                VendorCurrency::find($vendorTransaction->vendor_currency_id)->increment('amount', $vendorTransaction->total);
            }
        }
        AccountLog::where([
            'action'    => 'vendor_transaction',
            'action_id'   => $vendorTransaction->id,
        ])->forceDelete();
        $vendorTransaction->forceDelete();
        return redirect()->route('vendor_transaction.index')->with('success', 'vendor Transaction  Deleted');
    }

    public function getVendorCurrencies($id)
    { //used in SELF TRANSFER AJAX REQUEST
        $html = '';

        $currencies = VendorCurrency::where('vendor_id', $id)->get();
        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($currencies as $obj) {
            $html .= '<option value="' . $obj->id . '" data-treasury="' . $obj->currency_id . '" data-amount="' . $obj->amount . '">' . $obj->currency->name . '(' . $obj->amount . ')</option>';
        }
        return response()->json(['html' => $html]);
    }
    public function findCurrency($account_id, $currency_id)
    { //used in SELF TRANSFER AJAX REQUEST

        $account_id = Account::where('id', $account_id)->first();
        $currency_id = VendorCurrency::where('id', $currency_id)->first();
        $account_id = $account_id->currency_id;
        $currency_id = $currency_id->currency_id;

        return response()->json(['account_id' => $account_id, 'currency_id'   => $currency_id]);
    }
}
