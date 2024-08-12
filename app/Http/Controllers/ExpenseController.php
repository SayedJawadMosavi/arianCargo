<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Account;
use App\Http\Traits\AccountLogTrait;
use App\Models\AccountLog;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class ExpenseController extends Controller
{
    use AccountLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $settings;
    public function __construct(Request $request)
    {
        $this->middleware('permission:expense.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:expense.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:expense.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:expense.delete', ['only' => ['destroy']]);
        $this->middleware('permission:expense.restore', ['only' => ['restore']]);
        $this->middleware('permission:expense.forceDelete', ['only' => ['forceDelete']]);
        $this->settings = $request->get('settings');
    }

    public function index()
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
        $trashed = Expense::branch()->where('type','expense')->onlyTrashed()->whereBetween($column, [$from, $to])->latest()->get();
        $expenses = Expense::branch()->where('type','expense')->whereBetween($column, [$from, $to])->latest()->orderBy('id', 'desc')->get();

        return view('expense.index', compact('expenses', 'trashed'));
    }

    public function filterExpense(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $expenses = Expense::branch()->where('type','expense')->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Expense::branch()->where('type','expense')->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('expense.index', compact('expenses', 'trashed'));
    }
    public function filterIncome(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $incomes = Expense::branch()->where('type','income')->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Expense::branch()->where('type','income')->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('expense.income', compact('incomes', 'trashed'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseCategory::get();
        $main_currency = Setting::with('currency')->branch()->first();
        $settings = Setting::with('currency')->branch()->first();

        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        return view('expense.create', compact('categories', 'accounts', 'main_currency', 'settings'));
    }
    public function getIncomeData()
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
        $trashed = Expense::branch()->where('type','income')->onlyTrashed()->whereBetween($column, [$from, $to])->latest()->get();
        $incomes = Expense::branch()->where('type','income')->whereBetween($column, [$from, $to])->latest()->orderBy('id', 'desc')->get();


        return view('expense.income', compact('incomes', 'trashed'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'account_id' => 'required|integer',
            'type' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            if ($request->main_amount == null) {
                $total = $request->amount;
            } else {
                $total = $request->main_amount;
            }
            $account = Account::find($request->account_id);
            if ($request->type == "expense") {

                if ($account->amount < $request->amount) {
                    throw new \Exception('Insufficient account balance');
                }
            }
            $expense = Expense::create([
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'rate' => $request->rate,
                'main_amount' => $total,
                'description' => $request->name,
            'operation' => $request->exchange_type,
                'type' => $request->type,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'expense_category_id' => $request->category_id,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
            $type = ($request->type == "expense") ? 'withdraw' : 'deposit';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            Account::find($request->account_id)->{$request->type == "expense" ? 'decrement' : 'increment'}('amount', $request->amount);

            $account = Account::find($request->account_id);


            $flag = $this->InsertAccountLog($request->account_id, $type, $request->amount, $request->name, $account->amount, 'expense', $expense->id, $currentDate);


            if ($flag) {
                DB::commit();
                if ($request->type=="expense") {

                    return redirect()->route('expense.index')->with('success', 'expense stored successfully');
                }else{
                    return redirect()->route('income.index')->with('success', 'income stored successfully');

                }
            } else {
                DB::rollBack();
                return redirect()->route('expense.index')->with('error', 'expense Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating expense: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::get();
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $main_currency = Setting::with('currency')->branch()->get();
        return view('expense.create', compact('expense', 'accounts', 'categories', 'main_currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        DB::beginTransaction();
        try {

            if ($expense->type == 'income') {
                Account::find($expense->account_id)->decrement('amount', $expense->amount);
            } else {
                Account::find($expense->account_id)->increment('amount', $expense->amount);
            }
            $type = ($expense->type == "expense") ? 'withdraw' : 'deposit';
            $log = AccountLog::where(['action_id' => $expense->id, 'action' => 'expense'])->first();
            if ($log) {
                $log->amount = $request->amount;
                $log->type = $type;
                $log->save();
            }

            // ---------------------------------


            if ($request->account_currency != $request->main_currency_id) {
                $total = $request->main_amount;
                $rate = $request->rate;
            } else {
                $total = $request->amount;
                $rate = 1;
            }
            $flag = $expense->update([
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'description' => $request->name,
                'type' => $request->type,
                'main_amount' => $total,
                'rate' => $rate,

                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'expense_category_id' => $request->category_id,
                'branch_id' => auth()->user()->branch_id,
                'updated_by' => auth()->user()->id,
            ]);
            $type = ($request->type == "expense") ? 'withdraw' : 'deposit';
            $currentDate = isset($request->miladi_date) ? $request->miladi_date : $request->shamsi_date;
            Account::find($request->account_id)->{$request->type == "expense" ? 'decrement' : 'increment'}('amount', $request->amount);


            if ($flag) {
                DB::commit();
                return redirect()->route('expense.index')->with('success', 'expense updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('expense.index')->with('error', 'expense update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating expense: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        if ($expense->type == "income") {
            Account::find($expense->account_id)->decrement('amount', $expense->amount);
        } else {

            Account::find($expense->account_id)->increment('amount', $expense->amount);
        }
        AccountLog::where([
            'action'    => 'expense',
            'action_id'   => $expense->id,
        ])->delete();

        $expense->delete();
        return redirect()->route('expense.index')->with('success', 'Expense Deleted');
    }
    public function restore($id)
    {

        $expense =  Expense::withTrashed()->find($id);
        if ($expense->type == "income") {

            Account::find($expense->account_id)->increment('amount', $expense->amount);
        } else {
            Account::find($expense->account_id)->decrement('amount', $expense->amount);
        }
        Expense::where('id', $id)->withTrashed()->restore();
        AccountLog::withTrashed()->where(['action' => 'expense'])->where('action_id', $expense->id)->restore();

        return redirect()->route('expense.index')->with('success', 'Expense Restored');
    }
    public function forceDelete($id)
    {

        $expense =  Expense::withTrashed()->find($id);
        Expense::where('id', $id)->withTrashed()->forceDelete();
        AccountLog::withTrashed()->where(['action' => 'expense'])->where('action_id', $expense->id)->forceDelete();

        return redirect()->route('expense.index')->with('success', 'Expense Force Deleted');
    }
    function getData($id)
    {

        $data = '';
        $assets = ExpenseCategory::where('type', $id)->get();
        $data .= '<option disabled selected>'. __('home.category').'</option>';
        foreach ($assets as $d) {
            $data .= '<option value="' . $d->id . '">' . $d->name . '</option>';
        }
        return response()->json([
            'data' => $data

        ]);
    }

    public function getCurrencyData($id)
    {
        try {
            $currency = Account::where('id', $id)->with('currency')->first();
            return response()->json(['data' => $currency]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
