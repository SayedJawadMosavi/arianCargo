<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Cargo;
use App\Models\CargoPayment;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
// use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class DashboardController extends Controller
{
    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
    }
    public function index()
    {
        if ($this->settings->date_type == 'shamsi') {
            $today =  datenow();

            $column = 'shamsi_date';
        } else {
            $today = date("Y-m-d");

            $column = 'miladi_date';
        }
        if (auth()->user()->type == "admin") {
            $cargos = Cargo::count('id');
            $clients = Client::count('id');
            $staffs = Staff::count('id');
            $users = User::with('center')->count('id');
        } else {
            $cargos = Cargo::branch()->count('id');
            $clients = Client::branch()->count('id');
            $staffs = Staff::branch()->count('id');
            $users = User::with('center')->count('id');


            $payments = CargoPayment::branch()->get();
            $expenses = Expense::branch()->where('type', 'expense')->get();

            $incomes = Expense::branch()->where('type', 'income')->get();
            $daily_payments = CargoPayment::branch()->whereDate('created_at', today())
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupByRaw('DATE(created_at)')
                ->get();
         $daily_expenses = Expense::branch()
    ->whereDate('created_at', today())
    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
    ->groupByRaw('DATE(created_at)')
    ->get();
            // dd($daily_payments);
            // $distribution = DistributeDetail::branch()->with('kit', 'center', 'distribution')->get();
        }




        return view('dashboard', compact('cargos', 'clients','daily_expenses', 'staffs', 'users', 'payments', 'expenses', 'incomes', 'daily_payments'));
    }

    public function journal()
    {
        $deposit   = [];
        $withdraw   = [];
        if ($this->settings->date_type == 'shamsi') {
            $from =  datenow();
            $to =  datenow();
            $column = 'shamsi_date';
        } else {
            $from = date("Y-m-d");
            $to = date("Y-m-d");
            $column = 'miladi_date';
        }

        $logs = AccountLog::with('account')->branch()->whereBetween($column, [$from, $to])->latest()->get();
        foreach (Currency::where('active', 1)->get() as $obj) {

            $deposit[$obj->name] = AccountLog::with('account.currency')->whereBetween($column, [$from, $to])
                ->where('type', 'deposit')
                ->join('accounts as t', 't.id', 'account_logs.account_id')->where('t.currency_id', $obj->id)->where('account_logs.branch_id', auth()->user()->branch_id)->sum('account_logs.amount');

            $withdraw[$obj->name] = AccountLog::with('account.currency')->whereBetween($column, [$from, $to])
                ->where('type', 'withdraw')
                ->join('accounts as t', 't.id', 'account_logs.account_id')->where('t.currency_id', $obj->id)->where('account_logs.branch_id', auth()->user()->branch_id)->sum('account_logs.amount');
        }

        return view('dashboard.statement', compact('logs', 'deposit', 'withdraw'));
    }

    public function filterJournal(Request $request)
    {

        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $logs = AccountLog::with('account')->branch()->whereBetween($column, [$from, $to])->latest()->get();
        foreach (Currency::where('active', 1)->get() as $obj) {

            $deposit[$obj->name] = AccountLog::with('account.currency')->whereBetween($column, [$from, $to])
                ->where('type', 'deposit')
                ->join('accounts as t', 't.id', 'account_logs.account_id')->where('t.currency_id', $obj->id)->where('account_logs.branch_id', auth()->user()->branch_id)->sum('account_logs.amount');

            $withdraw[$obj->name] = AccountLog::with('account.currency')->whereBetween($column, [$from, $to])
                ->where('type', 'withdraw')
                ->join('accounts as t', 't.id', 'account_logs.account_id')->where('t.currency_id', $obj->id)->where('account_logs.branch_id', auth()->user()->branch_id)->sum('account_logs.amount');
        }
        return view('dashboard.statement', compact('logs', 'deposit', 'withdraw'));
    }
}
