<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountLog;
use App\Models\AccountTransfer;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAccountTransferRequest;
use App\Http\Requests\UpdateAccountTransferRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
class AccountTransferController extends Controller
{
    use AccountLogTrait;

    public function __construct()
    {
        $this->middleware('permission:account_transfer.view', ['only' => ['index']]);
        $this->middleware('permission:account_transfer.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:account_transfer.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:account_transfer.delete', ['only' => ['destroy']]);
        $this->middleware('permission:account_transfer.restore', ['only' => ['restore']]);
        $this->middleware('permission:account_transfer.forceDelete', ['only' => ['forceDelete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed = AccountTransfer::branch()->onlyTrashed()->get();
        $account_transfers = AccountTransfer::orderBy('id', 'desc')->get();

        return view('account_transfer.index', compact('account_transfers', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::branch()->where('amount', '>', 0)->get();
        $clients = Client::get();
        return view('account_transfer.create', compact('accounts', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountTransferRequest $request)
    {
        DB::beginTransaction();
        try {

            $from_account = Account::find($request->from_account);
            $to_account = Account::find($request->to_account);
            if ($from_account->amount < $request->amount) {
                throw new \Exception('Transfer amount can not be bigger than account');
            }

            $total = $request->exchange_type == 'multiply' ? $request->amount * $request->rate : $request->amount / $request->rate;
            $account_transfer = AccountTransfer::create([

                'sender_account_id' => $request->from_account,
                'amount' => $request->amount,
                'receiver_account_id' => $request->to_account,
                'rate' => $request->rate,
                'operation' => $request->exchange_type,
                'total' => $total,
                'currency_id' => $from_account->currency_id,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $from_account->decrement('amount', $request->amount);
            $to_account->increment('amount', $total);

            $flag = $this->InsertAccountLog($request->from_account, 'withdraw', $request->amount, $request->description, $from_account->amount, 'account_transfer', $account_transfer->id, $currentDate);
            $flag = $this->InsertAccountLog($request->to_account, 'deposit', $total, $request->description, $to_account->amount, 'account_transfer', $account_transfer->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('account_transfer.index')->with('success', 'Account Transfer stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('account_transfer.index')->with('error', 'Account Transfer Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Account Transfer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountTransfer  $accountTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTransfer $accountTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountTransfer  $accountTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountTransfer $accountTransfer)
    {
        return redirect()->back()->with('error', 'You can not edit the account transfer, instead perform a reverse transactin');
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();

        return view('account_transfer.create', compact('accountTransfer', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountTransfer  $accountTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountTransferRequest $request, AccountTransfer $accountTransfer)
    {
        return redirect()->back()->with('error', 'You can not edit the account transfer, instead perform a reverse transactin');

        DB::beginTransaction();
        try {

                Account::find($accountTransfer->sender_account_id )->increment('amount', $accountTransfer->amount);
                Account::find($accountTransfer->receiver_account_id )->decrement('amount', $accountTransfer->amount);
                $from_account = Account::find($request->from_account);
                $to_account = Account::find($request->to_account);
            $flag = $accountTransfer->update([
                'sender_account_id' => $request->from_account,
                'amount' => $request->amount,
                'receiver_account_id' => $request->to_account,
                'currency_id' => $from_account->currency_id,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            Account::find($request->from_account)->decrement('amount', $request->amount);
            Account::find($request->to_account)->increment('amount', $request->amount);
            $from_accounts = Account::find($request->from_account);
            $to_accounts = Account::find($request->to_account);

            $log = AccountLog::where(['action_id' => $accountTransfer->id, 'action' => 'account_transfer','account_id'  =>$request->from_account])->update(['amount'   =>$request->amount,'balance'  =>$from_accounts->amount]);
            $log = AccountLog::where(['action_id' => $accountTransfer->id, 'action' => 'account_transfer','account_id'  =>$request->to_account])->update(['amount'   =>$request->amount,'balance'  =>$to_accounts->amount]);


            if ($flag) {
                DB::commit();
                return redirect()->route('account_transfer.index')->with('success', 'Account Transaction updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('account_transfer.index')->with('error', 'Account Transaction update Failed');
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
     * @param  \App\Models\AccountTransfer  $accountTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountTransfer $accountTransfer)
    {
        return redirect()->back()->with('error', 'You can not delete the account transfer, instead perform a reverse transactin');

        Account::find($accountTransfer->sender_account_id )->increment('amount', $accountTransfer->amount);
        Account::find($accountTransfer->receiver_account_id  )->decrement('amount', $accountTransfer->amount);
        AccountLog::where([
            'action'    => 'account_transfer',
            'action_id'   => $accountTransfer->id,
        ])->forceDelete();
       $accountTransfer->forceDelete();
       return redirect()->route('account_transfer.index')->with('success', 'Transfer Deleted');

    }

    public function getAccounts($id)
    { //used in SELF TRANSFER AJAX REQUEST
        $html = '';
        $account = Account::find($id);

        $currencies = Account::branch()->where('currency_id', $account->currency_id)->where('id' ,'!=',$id)->get();

        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($currencies as $obj) {
            $html .= '<option value="' . $obj->id . '" data-treasury="' . $obj->currency_id . '" data-amount="' . $obj->amount . '">' .$obj->name .' - '. $obj->currency->name . '(' . $obj->amount . ')</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function getOtherAccounts($id)
    { //used in Account TRANSFER AJAX REQUEST
        $html = '';
        $currencies = Account::branch()->where('id' ,'!=',$id)->get();

        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($currencies as $obj) {
            $html .= '<option value="' . $obj->id . '" data-treasury="' . $obj->currency_id . '" data-amount="' . $obj->amount . '">' .$obj->name .' - '. $obj->currency->name . '(' . $obj->amount . ')</option>';
        }
        return response()->json(['html' => $html]);
    }
}
