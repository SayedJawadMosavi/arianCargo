<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\ClientCurrency;
use App\Models\ClientTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientTransactionRequest;
use App\Http\Requests\UpdateClientTransactionRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Models\AccountLog;
use App\Http\Traits\ClientLogTrait;
use App\Models\ClientLog;
use App\Models\Currency;
use App\Models\StaffDepositWithdraw;

class ClientTransactionController extends Controller
{
    use AccountLogTrait;
    use ClientLogTrait;
    public function __construct()
    {
        $this->middleware('permission:client.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:client.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.delete', ['only' => ['destroy']]);
        $this->middleware('permission:client.restore', ['only' => ['restore']]);
        $this->middleware('permission:client.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposit   = [];
        $withdraw   = [];

        foreach (Currency::where('active', 1)->get() as $obj) {

            $deposit[$obj->name] = ClientTransaction::where('type', 'deposit')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereHas('client_currency', function ($query) use ($obj) {
                $query->where('currency_id', $obj->id);
            })
            ->sum('amount');

            $withdraw[$obj->name] = ClientTransaction::where('type', 'withdraw')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereHas('client_currency', function ($query) use ($obj) {
                $query->where('currency_id', $obj->id);
            })
            ->sum('amount');
        }

        $trashed = ClientTransaction::branch()->onlyTrashed()->get();
        $client_transactions = ClientTransaction::with('client_currency', 'client', 'account', 'client_currency.currency', 'account.currency')->orderBy('id', 'desc')->get();

        return view('client_transaction.index', compact('client_transactions', 'trashed', 'deposit', 'withdraw'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::branch()->get();
        $clients = Client::branch()->get();
        return view('client_transaction.create', compact('accounts', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientTransactionRequest $request)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $total = 0;
            $same = 'yes';
            $account = Account::find($request->account_id);
            if ($request->type == "withdraw") {
                if ($account->amount < $request->amount) {
                    throw new \Exception('Transaction can not be bigger than account');
                }
            }
            $client_currency = ClientCurrency::where('client_id', $request->client_id)->first();
            // dd($client_currency);
            $amount = $request->amount;
            // if($account->currency_id == $client_currency->currency_id){
            //     $amount = $request->amount;
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
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $client_transactions = ClientTransaction::create([
                'account_id' => $request->account_id,
                'client_id' => $request->client_id,
                'amount' => $amount,
                'client_currency_id' => $client_currency->id,
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


            $type = ($request->type == "withdraw") ? 'withdraw' : 'deposit';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            $flag = $account->{$type == "withdraw" ? 'decrement' : 'increment'}('amount', $amount);

            $flag = $this->InsertAccountLog($request->account_id, $type, $amount, $request->description, $account->amount, 'client_transaction', $client_transactions->id, $currentDate);

            // if($same == 'no'){
            //     $amount =  $total;
            // }
            $flag = $client_currency->{$type == "withdraw" ? 'decrement' : 'increment'}('amount', $amount);
            // dd($client_currency->amount);

            $this->InsertClientLog($request->client_id, $client_currency->id, $type, $amount, $request->description, $client_currency->amount, 'client_transaction', $client_transactions->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('client_transaction.index')->with('success', 'Client Transaction stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('client_transaction.index')->with('error', 'Client Transaction Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Client Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientTransaction  $clientTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ClientTransaction $clientTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientTransaction  $clientTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientTransaction $clientTransaction)
    {
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $clients = Client::branch()->get();
        return view('client_transaction.create', compact('clientTransaction', 'accounts', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientTransaction  $clientTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientTransactionRequest $request, ClientTransaction $clientTransaction)
    {
        DB::beginTransaction();
        try {
            $client_currency = ClientCurrency::where('client_id', $request->client_id)->first();

            if ($clientTransaction->exchange_rate == null && $clientTransaction->operation == null) {
                if ($clientTransaction->type == 'deposit') {
                    Account::find($clientTransaction->account_id)->decrement('amount', $clientTransaction->amount);
                    ClientCurrency::find($client_currency->id)->decrement('amount', $clientTransaction->amount);
                } else {
                    Account::find($clientTransaction->account_id)->increment('amount', $clientTransaction->amount);
                    ClientCurrency::find($client_currency->id)->increment('amount', $clientTransaction->amount);
                }
            } else {

                if ($clientTransaction->type == 'deposit') {
                    Account::find($clientTransaction->account_id)->decrement('amount', $clientTransaction->amount);
                    ClientCurrency::find($client_currency->id)->decrement('amount', $clientTransaction->total);
                } else {
                    Account::find($clientTransaction->account_id)->increment('amount', $clientTransaction->amount);
                    ClientCurrency::find($client_currency->id)->increment('amount', $clientTransaction->total);
                }
            }
            $flag = $clientTransaction->update([

                'account_id' => $request->account_id,
                'client_id' => $request->client_id,
                'amount' => $request->amount,
                'client_currency_id' => $client_currency->id,
                'description' => $request->description,
                'type' => $request->type,
                'operation' => $request->operation,
                'rate' => $request->exchange_rate,
                'total' => $request->total,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,

            ]);

            // if ($request->exchange_rate != null && $request->operation != null) {
            //     Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            //     ClientCurrency::find($client_currency->id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->total);
            // } else {
                Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
                ClientCurrency::find($client_currency->id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            // }
            $account=  Account::find($request->account_id);
            $client_currency=  ClientCurrency::find($client_currency->id);
            $log = AccountLog::where(['action_id' => $clientTransaction->id, 'action' => 'client_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'balance'  =>$account->amount]);
            // dd($client_currency->amount);
            $client_log = ClientLog::where(['action_id' => $clientTransaction->id, 'action' => 'client_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'available'  =>$client_currency->amount]);

            if ($flag) {
                DB::commit();
                return redirect()->route('client_transaction.index')->with('success', 'Account Transaction updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('client_transaction.index')->with('error', 'Account Transaction update Failed');
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
     * @param  \App\Models\ClientTransaction  $clientTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientTransaction $clientTransaction)
    {
        if ($clientTransaction->exchange_rate == null && $clientTransaction->operation == null) {
            if ($clientTransaction->type == "deposit") {
                Account::find($clientTransaction->account_id)->decrement('amount', $clientTransaction->amount);
                ClientCurrency::find($clientTransaction->client_currency_id)->decrement('amount', $clientTransaction->amount);
            } else {
                Account::find($clientTransaction->account_id)->increment('amount', $clientTransaction->amount);
                ClientCurrency::find($clientTransaction->client_currency_id)->increment('amount', $clientTransaction->amount);
            }
        } else {
            if ($clientTransaction->type == "deposit") {
                Account::find($clientTransaction->account_id)->decrement('amount', $clientTransaction->amount);
                ClientCurrency::find($clientTransaction->client_currency_id)->decrement('amount', $clientTransaction->total);
            } else {
                Account::find($clientTransaction->account_id)->increment('amount', $clientTransaction->amount);
                ClientCurrency::find($clientTransaction->client_currency_id)->increment('amount', $clientTransaction->total);
            }
        }
        AccountLog::where([
            'action'    => 'client_transaction',
            'action_id'   => $clientTransaction->id,
        ])->forceDelete();

        ClientLog::where([
            'action'    => 'client_transaction',
            'action_id'   => $clientTransaction->id,
        ])->delete();

        $clientTransaction->forceDelete();
        return redirect()->route('client_transaction.index')->with('success', 'Client Transaction  Deleted');
    }

    public function getClientCurrency($id)
    { //used in SELF TRANSFER AJAX REQUEST


        $currencies = ClientCurrency::with('currency')->where('client_id', $id)->first();

        return response()->json(['data' => $currencies]);
    }
    public function findCurrency($account_id, $currency_id)
    { //used in SELF TRANSFER AJAX REQUEST

        $account_id = Account::where('id', $account_id)->first();
        $currency_id = ClientCurrency::where('id', $currency_id)->first();
        $account_id = $account_id->currency_id;
        $currency_id = $currency_id->currency_id;

        return response()->json(['account_id' => $account_id, 'currency_id'   => $currency_id]);
    }
}
