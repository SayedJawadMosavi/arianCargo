<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Http\Requests\StoreAccountTransactionRequest;
use App\Http\Requests\UpdateAccountTransactionRequest;

class AccountTransactionController extends Controller
{
    use AccountLogTrait;
    public function __construct()
    {
        $this->middleware('permission:account_transaction.view', ['only' => ['index']]);
        $this->middleware('permission:account_transaction.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:account_transaction.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:account_transaction.delete', ['only' => ['destroy']]);
        $this->middleware('permission:account_transaction.restore', ['only' => ['restore']]);
        $this->middleware('permission:account_transaction.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed = AccountLog::with('account')->branch()->onlyTrashed()->get();
        $account_transactions = AccountLog::with('account')->orderBy('id', 'desc')->where('action', 'direct')->get();

        return view('account_transaction.index', compact('account_transactions', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $accounts = Account::branch()->get();
        return view('account_transaction.create', compact('accounts'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountTransactionRequest $request)
    {


        DB::beginTransaction();
        try {

            $type = ($request->type == "withdraw") ? 'withdraw' : 'deposit';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);
            $account = Account::find($request->account_id);
            if ($request->type == "withdraw") {
                if ($account->amount < $request->amount) {
                    throw new \Exception('Transaction can not be bigger than account');
                }
            }

            $flag = $this->InsertAccountLog($request->account_id, $type, $request->amount, $request->description, $account->amount, 'direct', null, $currentDate);
            if ($flag) {
                DB::commit();
                return redirect()->route('account_transaction.index')->with('success', 'Account Transaction stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('account_transaction.index')->with('error', 'Account Transaction Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Account Transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountTransaction  $AccountTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTransaction $AccountTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountTransaction  $AccountTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountLog $AccountTransaction)
    {


        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        return view('account_transaction.create', compact('AccountTransaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountTransaction  $AccountTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountTransactionRequest $request, AccountLog $AccountTransaction)
    {
        DB::beginTransaction();
        try {

            if ($AccountTransaction->type == 'deposit') {
                Account::find($AccountTransaction->account_id)->decrement('amount', $AccountTransaction->amount);
            } else {
                Account::find($AccountTransaction->account_id)->increment('amount', $AccountTransaction->amount);
            }
            $flag = $AccountTransaction->update([
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => $request->type,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'updated_by' => auth()->user()->id,
            ]);

            Account::find($request->account_id)->{$request->type == "withdraw" ? 'decrement' : 'increment'}('amount', $request->amount);


            if ($flag) {
                DB::commit();
                return redirect()->route('account_transaction.index')->with('success', 'Account Transaction updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('account_transaction.index')->with('error', 'Account Transaction update Failed');
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
     * @param  \App\Models\AccountTransaction  $AccountTransaction
     * @return \Illuminate\Http\Response
     */

    public function destroy(AccountLog $AccountTransaction)
    {
        if ($AccountTransaction->type == "deposit") {
            Account::find($AccountTransaction->account_id)->decrement('amount', $AccountTransaction->amount);
        } else {

            Account::find($AccountTransaction->account_id)->increment('amount', $AccountTransaction->amount);
        }
        $AccountTransaction->delete();
        return redirect()->route('account_transaction.index')->with('success', 'Account Transaction Deleted');
    }
    public function restore($id)
    {

        $accountLog =  AccountLog::withTrashed()->find($id);
        if ($accountLog->type == "deposit") {

            Account::find($accountLog->account_id)->increment('amount', $accountLog->amount);
        } else {
            Account::find($accountLog->account_id)->decrement('amount', $accountLog->amount);
        }
        AccountLog::where('id', $id)->withTrashed()->restore();


        return redirect()->route('account_transaction.index')->with('success', 'Account Transaction Restored');
    }
    public function forceDelete($id)
    {
        AccountLog::where('id', $id)->withTrashed()->forceDelete();
        return redirect()->route('account_transaction.index')->with('success', 'Account Transaction Force Deleted');
    }
}
