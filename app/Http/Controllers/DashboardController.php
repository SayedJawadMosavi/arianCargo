<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\Setting;
use App\Models\Slider;
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
        $products=0;
        $costs=0;
        // $products = Product::branch()->count('quantity');
        // $costs = Product::branch()->sum('cost');
        $daily_total_sell = Sell::branch()->where($column, $today)->sum('total');
        $daily_total_purchase = Purchase::branch()->where($column, $today)->sum('total');
        $daily_total_expense = Expense::branch()->where('type', 'expense')->where($column, $today)->sum('amount');
        $daily_total_cash_received = Expense::branch()->where('type', 'income')->where($column, $today)->sum('amount');

        $dailySales = Sell::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
            ->get();

        // Fetch total purchase amount for each day
        $dailyPurchases = Purchase::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_purchases')
        )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
            ->get();

        $settings = Setting::where('branch_id', auth()->user()->branch_id)->first();

        return view('dashboard', compact('products', 'costs', 'dailySales', 'dailyPurchases', 'daily_total_sell', 'daily_total_purchase', 'daily_total_expense', 'daily_total_cash_received', 'settings'));
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
