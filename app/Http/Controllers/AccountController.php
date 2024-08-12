<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Traits\AccountLogTrait;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class AccountController extends Controller
{
    use AccountLogTrait;
    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:account.create',['only' => ['create', 'store']]);
        $this->middleware('permission:account.edit',['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:account.view',['only' => ['index', 'statement']]);
        $this->middleware('permission:account.delete',['only' => ['destroy']]);
        $this->middleware('permission:account.restore',['only' => ['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $accounts = Account::branch()->with('currency')->get();
        $groupedAccounts = $accounts->groupBy('currency.name');

        $sumsByCurrency = $groupedAccounts->map(function ($group) {
            return $group->sum('amount');
        });

        return view('account.index', compact('accounts', 'sumsByCurrency'));


        // $accounts = Account::branch()->get();
        // return view('account.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::branch()->get();
        return view('account.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountRequest $request)
    {

        DB::beginTransaction();
        try {

            if ($request->amount < 0) {
                throw new \Exception('Account balance less than zero not allowed');
            }
            isset($request->default) ? $default = 1: $default = 0;

            $account = new Account();
            $attributes = $request->only($account->getFillable());
            $attributes['user_id'] = auth()->user()->id;
            $attributes['active'] = 1;
            $attributes['default'] = $default;
            $attributes['branch_id'] = auth()->user()->branch_id;
            $account =  $account->create($attributes);
            // DD($account);
            isset($account) ? $flag = true : $flag = false;
            if ($this->settings->date_type == 'shamsi') {
                $currentDate = datenow();
            } else {
                $currentDate = date('Y-m-d');
            }
            if($request->amount > 0){
                $flag = $this->InsertAccountLog($account->id, 'deposit', $request->amount, $request->description, $request->amount, 'direct', null, $currentDate);
            }

            if ($flag) {
                DB::commit();
                return redirect()->route('account.index')->with('success', 'Account created successfully');
            } else {
                DB::rollBack();
                return redirect()->route('account.index')->with('error', 'Account Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating account: ' . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $currencies = Currency::branch()->get();
        return view('account.create', compact('account', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAccountRequest  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $active = isset($request->active) ? 1 : 0;
        $default = isset($request->default) ? 1 : 0;
        // // dd($active);
        // $attributes = $request->only($account->getFillable());
        // $attributes['active'] = $active;
        // $attributes['default'] = $default;
        // $attributes['branch_id'] = auth()->user()->branch_id;
        // $account->update($attributes);
        $account->update([
            'name' => $request->name,
            'description' => $request->description,
            'active' => $active,
            'default' => $default,
        ]);

        $accounts = Account::branch()->get();
        return redirect()->route('account.index', compact('accounts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }

    public function changeStatus($id)
    {
        $active = '';
        $account = Account::find($id);
        try {
            if ($account->active==1) {
                $account->update(['active'  =>0]);
                $active = 'Account Deactivated';

            }else if ($account->active==0) {
                $account->update(['active'  =>1]);
                $active = 'Account Activated';
            }
            return redirect()->route('account.index')->with('success', $active);
        } catch (\Throwable $th) {
            return redirect()->route('account.index')->with('error', 'Status update failed');
        }
    }

    public function statement(Account $account, Request $request){

        if ($this->settings->date_type == 'shamsi') {
            $from =  datenow();
            $to =  datenow();
            $column = 'shamsi_date';
        } else {
            $from = date("Y-m-d");
            $to = date("Y-m-d");
            $column = 'miladi_date';
        }

        $logs = AccountLog::where('account_id', $account->id)->with('account')->whereBetween($column, [$from, $to])->get();

        return view('account.statement', compact('logs', 'account'));

    }

    public function getStatement(Account $account, Request $request){

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }

        $logs = AccountLog::where('account_id', $account->id)->with('account')->whereBetween($column, [$from, $to])->get();
        return view('account.statement', compact('logs', 'account'));

    }
}
