<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Traits\AccountLogTrait;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\AccountPayment;
use App\Models\Branch;
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
        $this->middleware('permission:account.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:account.edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:account.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:account.delete', ['only' => ['destroy']]);
        $this->middleware('permission:account.restore', ['only' => ['restore']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user_id  = auth()->user()->id;

        $branch =  Branch::where('user_id', $user_id)->first();
        if ($branch->is_main_branch == 1) {

            $accounts = Account::with('currency', 'branchs')->get();
        } else {

            $accounts = Account::branch()->with('currency')->get();
        }

        $groupedAccounts = $accounts->groupBy('currency.name');

        $sumsByCurrency = $groupedAccounts->map(function ($group) {
            return $group->sum('amount');
        });

        return view('account.index', compact('accounts', 'sumsByCurrency', 'branch'));


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
        $currencies = Currency::active()->get();
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
            isset($request->default) ? $default = 1 : $default = 0;

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
            if ($request->amount > 0) {
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
    public function show($id)
    {
        $accounts = Account::with(['currency', 'payments'])->findOrFail($id);

        return view('account.payment', compact('accounts'));
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
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $account_payment = AccountPayment::find($id);

            $account = Account::where('id', $account_payment->account_id)->first();

            $branch = Branch::where('is_main_branch', 1)->first();
            $main_branch_account = Account::where('branch_id', $branch->id)->where('currency_id', $account->currency_id)->first();

            Account::find($account_payment->account_id)->increment('amount', $account_payment->amount);
            Account::find($account_payment->account_id)->decrement('paid_amount', $account_payment->amount);
            $main_branch_account->decrement('amount', $account_payment->amount);

            AccountLog::where([
                'action'    => 'branch_payment',
                'action_id'   => $account_payment->id,
            ])->forceDelete();


            $account_payment->forceDelete();
            DB::commit();

            return redirect()->back()->with('success', 'Branch Payment Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }

    public function changeStatus($id)
    {
        $active = '';
        $account = Account::find($id);
        try {
            if ($account->active == 1) {
                $account->update(['active'  => 0]);
                $active = 'Account Deactivated';
            } else if ($account->active == 0) {
                $account->update(['active'  => 1]);
                $active = 'Account Activated';
            }
            return redirect()->route('account.index')->with('success', $active);
        } catch (\Throwable $th) {
            return redirect()->route('account.index')->with('error', 'Status update failed');
        }
    }

    public function statement(Account $account, Request $request)
    {

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

    public function getStatement(Account $account, Request $request)
    {

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

    public function pay(Request $request)
    {
        $request->validate([
            'account_id'   => 'required|exists:accounts,id',
            'amount'       => 'required|numeric|min:1',
            'date'         => 'nullable|date',
            'description'  => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        // dd($request->all());
        try {
            $account = Account::where('id', $request->account_id)->first();

            $branch = Branch::where('is_main_branch', 1)->first();
            $main_branch_account = Account::where('branch_id', $branch->id)->where('currency_id', $account->currency_id)->first();

            $amountToPay = $request->amount;
            $currentDate = $request->shamsi_date ?? $request->miladi_date;
            $description = 'Branch Payment: - ' . ($request->description ?? '');

            // 1. ثبت پرداخت
            $payment = AccountPayment::create([
                'account_id'   => $account->id,
                'amount'       => $amountToPay,
                'description'  => $request->description,
                'shamsi_date'  => $currentDate,
                'miladi_date'  => $currentDate,
                'branch_id'    => auth()->user()->branch_id,
                'user_id'      => auth()->user()->id,
            ]);




            if ($amountToPay > $account->cargo_amount) {
                return back()->with('error', 'Payment amount exceeds the remaining balance.');
            }
            $account->paid_amount += $amountToPay;
            $account->decrement('amount', $amountToPay);

            $account->save();
            $main_branch_account->increment('amount', $amountToPay);
            $this->InsertAccountLog2(
                $main_branch_account->id,
                'deposit',
                $amountToPay,
                $description,
                $main_branch_account->amount,
                'branch_payment',
                $payment->id,
                $branch->id,
                $currentDate
            );

            $this->InsertAccountLog2(
                $account->id,
                'withdraw',
                $amountToPay,
                $description,
                $account->amount,
                'branch_payment',
                $payment->id,
                auth()->user()->branch_id,
                $currentDate
            );

            DB::commit();

            return back()->with('success', 'Payment done SuccessFully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا هنگام پرداخت: ' . $e->getMessage());
        }
    }

    public function updatePayment(Request $request, AccountPayment $cargoPayment)
    {
        DB::beginTransaction();
        try {
            // Fetch the existing payment
            $payment = AccountPayment::findOrFail($request->id);

            // Get the original account and related main branch account
            $account = Account::findOrFail($payment->account_id);
            $branch = Branch::where('is_main_branch', 1)->first();
            $main_branch_account = Account::where('branch_id', $branch->id)
                ->where('currency_id', $account->currency_id)
                ->first();

            // 1. Rollback old payment
            $account->decrement('paid_amount', $payment->amount);
            $account->increment('amount', $payment->amount);
            $main_branch_account->decrement('amount', $payment->amount);

            // 2. Apply new payment
            $account->increment('paid_amount', $request->amount);
            $account->decrement('amount', $request->amount);
            $main_branch_account->increment('amount', $request->amount);

            // Refresh account balances after updates
            $account->refresh();
            $main_branch_account->refresh();

            // Prepare description and date
            $description = 'Branch Payment: - ' . ($request->description ?? '');
            $currentDate = $request->shamsi_date
                ?? $request->miladi_date
                ?? today()->format('Y-m-d');

            // Remove old logs
            AccountLog::where(['action_id' => $payment->id, 'action' => 'branch_payment'])->delete();

            // Update payment info
            $payment->update([
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            // Insert new logs
           $this->InsertAccountLog2(
                $main_branch_account->id,
                'deposit',
                $request->amount,
                $description,
                $main_branch_account->amount,
                'branch_payment',
                $payment->id,
                $branch->id,
                $currentDate
            );

            $this->InsertAccountLog2(
                $account->id,
                'withdraw',
                $request->amount,
                $description,
                $account->amount,
                'branch_payment',
                $payment->id,
                auth()->user()->branch_id,
                $currentDate
            );

            DB::commit();
            return response()->json(['success', 'Updating Account Payment']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating Account Payment: ' . $e->getMessage());
        }
    }
}
