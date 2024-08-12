<?php

namespace App\Http\Controllers;

use App\Models\ShareholderTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShareholderTransactionRequest;
use App\Http\Requests\UpdateShareholderTransactionRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Http\Traits\CurrencyTrait;
use App\Models\AccountLog;
use App\Http\Traits\ShareholderLogTrait;
use App\Models\Account;
use App\Models\Currency;
use App\Models\ShareHolder;
use App\Models\ShareholderCurrency;
use App\Models\ShareholderLog;
class ShareholderTransactionController extends Controller
{
    use AccountLogTrait;
    use ShareholderLogTrait;
    use CurrencyTrait;
    public function __construct()
    {

        $this->middleware('permission:shareholder_transaction.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:shareholder_transaction.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:shareholder_transaction.delete', ['only' => ['destroy']]);
        $this->middleware('permission:shareholder_transaction.restore', ['only' => ['restore']]);
        $this->middleware('permission:shareholder_transaction.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed = ShareholderTransaction::branch()->onlyTrashed()->get();
        $shareholder_transactions = ShareholderTransaction::with('shareholder_currency', 'share_holder', 'account', 'shareholder_currency.currency', 'account.currency')->orderBy('id', 'desc')->get();

        return view('shareholder_transaction.index', compact('shareholder_transactions', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::branch()->get();
        $shareholders = ShareHolder::get();
        $currencies = Currency::active()->get();

        return view('shareholder_transaction.create', compact('accounts', 'shareholders', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShareholderTransactionRequest $request)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $total = 0;
            $same = 'yes';
            $account = Account::find($request->account_id);
            if ($request->type == "withdraw") {
                if ($account->amount < $request->amount) {
                    throw new \Exception('Insufficient account balance');
                }
            }

            $shareholder_currency = $this->GetShareholderCurrency($request->shareholder, $account->currency_id, $amount = 0);

            $shareholder_transactions = ShareholderTransaction::create([
                'account_id' => $request->account_id,
                'share_holder_id' => $request->shareholder,
                'amount' => $request->amount,
                'shareholder_currency_id' => $shareholder_currency->id,
                'description' => $request->description,
                'shamsi_date' => $request->shamsi_date,
                'miladi_date' => $request->miladi_date,
                'type' => $request->type,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);


            $type = ($request->type == "withdraw") ? 'withdraw' : 'deposit';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            $flag = $account->{$type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);

            $flag = $this->InsertAccountLog($request->account_id, $type, $request->amount, $request->description, $account->amount, 'shareholder_transaction', $shareholder_transactions->id, $currentDate);

            if($same == 'no'){
                $amount =  $total;
            }
            $flag = $shareholder_currency->{$type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);

            $this->InsertShareholderLog($request->shareholder, $shareholder_currency->id, $type, $request->amount, $request->description, $shareholder_currency->amount, 'share_holder_transaction', $shareholder_transactions->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('shareholder_transaction.index')->with('success', 'Shareholder Transaction stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('shareholder_transaction.index')->with('error', 'Shareholder Transaction Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Shareholder Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShareholderTransaction  $shareholderTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ShareholderTransaction $shareholderTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShareholderTransaction  $shareholderTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ShareholderTransaction $shareholderTransaction)
    {
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $shareholders = ShareHolder::get();
        return view('shareholder_transaction.create', compact('shareholderTransaction', 'accounts', 'shareholders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShareholderTransaction  $shareholderTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShareholderTransactionRequest $request, ShareholderTransaction $shareholderTransaction)
    {
        DB::beginTransaction();
        try {

            if ($shareholderTransaction->type == 'deposit') {
                Account::find($shareholderTransaction->account_id)->decrement('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->decrement('amount', $shareholderTransaction->amount);
            } else {
                Account::find($shareholderTransaction->account_id)->increment('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->increment('amount', $shareholderTransaction->amount);
            }

            $account = Account::find($request->account_id);
            if ($request->type == "withdraw") {
                if ($account->amount < $request->amount) {
                    throw new \Exception('Transaction can not be bigger than account');
                }
            }
            $shareholder_currency = $this->GetShareholderCurrency($request->shareholder, $account->currency_id, $amount = 0);


            $flag = $shareholderTransaction->update([
                'account_id' => $request->account_id,
                'share_holder_id' => $request->shareholder,
                'amount' => $request->amount,
                'shareholder_currency_id' => $shareholder_currency->id,
                'description' => $request->description,
                'type' => $request->type,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,
            ]);

            if ($request->exchange_rate != null && $request->operation != null) {
                Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
                ShareholderCurrency::find($request->shareholder_account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->total);
            } else {
                Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
                ShareholderCurrency::find($request->shareholder_account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            }
            $account=  Account::find($request->account_id);
            $log = AccountLog::where(['action_id' => $shareholderTransaction->id, 'action' => 'shareholder_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'balance'  =>$account->amount]);
            $client_log = ShareholderLog::where(['action_id' => $shareholderTransaction->id, 'action' => 'share_holder_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'available'  =>$account->amount]);

            if ($flag) {
                DB::commit();
                return redirect()->route('shareholder_transaction.index')->with('success', 'Account Transaction updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('shareholder_transaction.index')->with('error', 'Account Transaction update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating Account Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShareholderTransaction  $shareholderTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShareholderTransaction $shareholderTransaction)
    {

        if ($shareholderTransaction->exchange_rate == null && $shareholderTransaction->operation == null) {
            if ($shareholderTransaction->type == "deposit") {
                Account::find($shareholderTransaction->account_id)->decrement('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->decrement('amount', $shareholderTransaction->amount);
            } else {
                Account::find($shareholderTransaction->account_id)->increment('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->increment('amount', $shareholderTransaction->amount);
            }
        } else {
            if ($shareholderTransaction->type == "deposit") {
                Account::find($shareholderTransaction->account_id)->decrement('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->decrement('amount', $shareholderTransaction->total);
            } else {
                Account::find($shareholderTransaction->account_id)->increment('amount', $shareholderTransaction->amount);
                ShareholderCurrency::find($shareholderTransaction->shareholder_currency_id)->increment('amount', $shareholderTransaction->total);
            }
        }
        AccountLog::where([
            'action'    => 'shareholder_transaction',
            'action_id'   => $shareholderTransaction->id,
        ])->forceDelete();

        ShareholderLog::where([
            'action'    => 'share_holder_transaction',
            'action_id'   => $shareholderTransaction->id,
        ])->delete();

        $shareholderTransaction->forceDelete();
        return redirect()->route('shareholder_transaction.index')->with('success', 'Shareholder Transaction  Deleted');
    }


    public function getShareholderCurr($id)
    { //used in SELF TRANSFER AJAX REQUEST
        $html = '';

        $currencies = ShareholderCurrency::where('share_holders_id', $id)->get();
        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($currencies as $obj) {
            $html .= '<option value="' . $obj->id . '" data-treasury="' . $obj->currency_id . '" data-amount="' . $obj->amount . '">' . $obj->currency->name . '(' . $obj->amount . ')</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function getShareholderCurrencyJson($id)
    { //used in SELF TRANSFER AJAX REQUEST
        $data = ShareholderCurrency::with('currency')->where('share_holders_id', $id)->get();
        return response()->json(['data' => $data]);
    }

    public function findCurrency($account_id, $currency_id)
    { //used in SELF TRANSFER AJAX REQUEST

        $account_id = Account::where('id', $account_id)->first();
        $currency_id = ShareholderCurrency::where('id', $currency_id)->first();
        $account_id = $account_id->currency_id;
        $currency_id = $currency_id->currency_id;

        return response()->json(['account_id' => $account_id, 'currency_id'   => $currency_id]);
    }
}
