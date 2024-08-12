<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientLog;
use App\Models\ClientTransfer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Shareholder;
use Illuminate\Http\Request;
use App\Models\ClientCurrency;
use App\Models\Currency;
use App\Models\MainTransfer;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\Sell;
use App\Models\SellDetail;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockTransfer;
use App\Models\Vendor;
use App\Models\VendorCurrency;
use App\Http\Traits\AccountLogTrait;
use App\Models\Assets;
use App\Models\Rate;
use App\Models\ShareholderCurrency;
use App\Models\StockSubProduct;
use App\Models\StockTransferDetail;
use App\Models\SubProduct;

include "PersianCalendar.php";

use Morilog\Jalali\Jalalian;

class ReportController extends Controller
{
    protected $settings, $branch_base;
    use AccountLogTrait;
    public function __construct(Request $request)
    {
        // $this->middleware('permission:report.purchase',['only' => ['statementReport']]);
        // $this->middleware('permission:report.expense ',['only' => ['expenseReport', 'getExpenseReport']]);
        // $this->middleware('permission:report.withdraw_report',['only' => ['withdrawReport']]);
        // $this->middleware('permission:report.deposit_report',['only' => ['depositReport']]);
        $this->settings = $request->get('settings');

        // $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        // $this->branch_base = $base_currency->currency_id; //

    }

    public function expenseReport()
    {
        $categories =  ExpenseCategory::where('type', 'expense')->get();
        return view('report.expense', compact('categories'));
    }

    public function getExpenseReport(Request $request)
    {
        $from = isset($request->from) ? $request->from : datenow();
        $to = isset($request->to) ? $request->to : datenow();

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }
        if ($request->category_id == 'all') {
            $logs = Expense::branch()->whereBetween($column, [$from, $to])->where('type', 'expense')->get();
        } else {
            $logs = Expense::branch()->whereBetween($column, [$from, $to])->where(['expense_category_id' => $request->category_id, 'type' => 'expense'])->get();
        }
        $categories =  ExpenseCategory::where('type', 'expense')->get();

        return view('report.expense', compact('logs', 'categories'));
    }

    public function incomeReport()
    {
        $categories =  ExpenseCategory::where('type', 'income')->get();
        return view('report.income', compact('categories'));
    }

    public function getIncomeReport(Request $request)
    {
        $from = isset($request->from) ? $request->from : datenow();
        $to = isset($request->to) ? $request->to : datenow();

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }
        if ($request->category_id == 'all') {
            $logs = Expense::branch()->whereBetween($column, [$from, $to])->where('type', 'income')->get();
        } else {
            $logs = Expense::branch()->whereBetween($column, [$from, $to])->where(['expense_category_id' => $request->category_id, 'type' => 'income'])->get();
        }
        $categories =  ExpenseCategory::where('type', 'income')->get();

        return view('report.income', compact('logs', 'categories'));
    }

    public function sellReport()
    {
        return view('report.sell');
    }

    public function getSellReport(Request $request)
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

        // if ($this->settings->date_type == 'shamsi') {
        //     $to = $request->to_shamsi;
        //     $from = $request->from_shamsi;
        //     $column = 'shamsi_date';
        // } else {
        //     $to = $request->to_miladi;
        //     $from = $request->from_miladi;
        //     $column = 'miladi_date';
        // }
        $logs = SellDetail::where('s.branch_id', auth()->user()->branch_id)->join('sells AS s', 's.id', 'sell_details.sell_id')
            ->whereBetween('s.' . $column, [$from, $to])->get();

        $branch_base = $this->branch_base;
        return view('report.sell', compact('logs', 'branch_base'));
    }

    public function purchaseReport()
    {
        return view('report.purchase');
    }

    public function getPurchaseReport(Request $request)
    {
        $from = isset($request->from) ? $request->from : datenow();
        $to = isset($request->to) ? $request->to : datenow();

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }
        $logs = PurchaseDetail::where('s.branch_id', auth()->user()->branch_id)->join('purchases AS s', 's.id', 'purchase_details.purchase_id')
            ->whereBetween('s.' . $column, [$from, $to])->get();

        return view('report.purchase', compact('logs'));
    }
    public function AvailableReport()
    {
        $stocks =  Stock::get();
        return view('report.available_stock', compact('stocks'));
    }

    public function getAvailableReport(Request $request)
    {

        $branchId = auth()->user()->branch_id;
        if ($request->stock_id == "all") {

            $logs = StockSubProduct::whereHas('stockProduct', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })->get();
        } else {
            $logs = StockSubProduct::where('available', '>', 0)->whereHas('stockProduct', function ($query) use ($branchId, $request) {
                $query->where('branch_id', $branchId)->where('stock_id', $request->stock_id);
            })->get();
        }
        $stocks =  Stock::get();

        return view('report.available_stock', compact('logs', 'stocks'));
    }

    public function MainStockReport()
    {
        $categories =  Category::get();
        return view('report.main_stock', compact('categories'));
    }

    public function getMainStockReport(Request $request)
    {
        if ($request->category_id == "all") {
            $logs = SubProduct::with('product')->branch()->where('available', '>', 0)->get();
        } else {
            $logs =  SubProduct::with('product')->branch()->where('available', '>', 0)->whereHas('product', function($q) use ($request){
                $q->where('category_id', $request->category_id);
            })->get();
        }
        $categories =  Category::get();

        // dd($logs);
        return view('report.main_stock', compact('logs', 'categories'));
    }
    public function StockTransferReport()
    {
        $stocks =  Stock::get();
        return view('report.stock_transfer', compact('stocks'));
    }

    public function getStockTransferReport(Request $request)
    {
        $from = isset($request->from) ? $request->from : datenow();
        $to = isset($request->to) ? $request->to : datenow();

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }
        if ($request->from_stock == "all") {
            // dd('hey');
            $logs = StockTransferDetail::branch()->whereHas('stock_transfer', function($q) use($column, $to, $from){
                $q->whereBetween($column, [$from, $to]);
            })->get();
        } else {
            $logs = StockTransferDetail::branch()->whereHas('stock_transfer', function($q) use($column, $to, $from, $request){
                $q->whereBetween($column, [$from, $to])->where('sender_stock_id', $request->from_stock);
            })->get();
        }
        $stocks =  Stock::get();
        return view('report.stock_transfer', compact('logs', 'stocks'));
    }
    public function MainTransferReport()
    {
        $stocks =  Stock::get();
        return view('report.main_transfer', compact('stocks'));
    }

    public function getMainTransferReport(Request $request)
    {
        $from = isset($request->from) ? $request->from : datenow();
        $to = isset($request->to) ? $request->to : datenow();

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }

        if ($request->stock_id == "all") {

            $logs = MainTransfer::branch()->whereBetween($column, [$from, $to])->get();
        } else {
            $logs =  MainTransfer::branch()->whereBetween($column, [$from, $to])->where('stock_id', $request->stock_id)->get();
        }
        $stocks =  Stock::get();
        return view('report.main_transfer', compact('logs', 'stocks'));
    }
    public function DueClientReport()
    {
        $clients =  Client::get();
        return view('report.due_client', compact('clients'));
    }

    public function getDueClientReport(Request $request)
    {

        if ($request->client_id == "all") {

            $sums = [];
            foreach (Currency::get() as $obj) {
                $sums[$obj->name] = ClientCurrency::where('currency_id', $obj->id)->where('amount', '<', 0)->sum('amount');
            }

            $logs = ClientCurrency::with('currency', 'client')->where('amount', '<', 0)->get();
        } else {
            $sums = [];

            foreach (Currency::get() as $obj) {
                $sums[$obj->name] = ClientCurrency::where('currency_id', $obj->id)->where('client_id', $request->client_id)->where('amount', '<', 0)->sum('amount');
            }
            $logs =  ClientCurrency::with('currency', 'client')->where('amount', '<', 0)->where('client_id', $request->client_id)->get();
        }
        $clients =  Client::get();

        return view('report.due_client', compact('logs', 'clients', 'sums'));
    }

    public function DueVendorReport()
    {
        $vendors =  Vendor::get();
        return view('report.due_vendor', compact('vendors'));
    }

    public function getDueVendorReport(Request $request)
    {

        if ($request->vendor_id == "all") {

            $sums = [];

            foreach (Currency::get() as $obj) {
                $sums[$obj->name] = VendorCurrency::where('currency_id', $obj->id)->where('amount', '<', 0)->sum('amount');
            }

            $logs = VendorCurrency::with('currency', 'vendor')->where('amount', '<', 0)->get();
        } else {
            $sums = [];

            foreach (Currency::get() as $obj) {
                $sums[$obj->name] = VendorCurrency::where('currency_id', $obj->id)->where('vendor_id', $request->vendor_id)->where('amount', '<', 0)->sum('amount');
            }
            $logs =  VendorCurrency::with('currency', 'vendor')->where('amount', '<', 0)->where('vendor_id', $request->vendor_id)->get();
        }
        $vendors =  Vendor::get();


        return view('report.due_vendor', compact('logs', 'vendors', 'sums'));
    }
    public function AllAvailableReport()
    {
        $branchId = auth()->user()->branch_id;

        $products = Product::with(['stockProducts' => function ($query) use ($branchId) {
            $query->whereHas('stock', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }])->get();

        $products->each(function ($product) {
            $totalQuantity = $product->stockProducts->sum('quantity');
            $product->totalQuantity = $totalQuantity;
        });



        return view('report.all_available_stock', compact('products'));
    }
    public function PrfitLostReport()
    {
        $branchId = auth()->user()->branch_id;

        $total_account_available = 0;
        $account =   Account::branch()->get();
        $clientCurrency =   ClientCurrency::branch()->where('amount', '>', 0)->get();
        $vendorCurrency =   VendorCurrency::branch()->where('amount', '>', 0)->get();
        $clientCurrency2 =   ClientCurrency::branch()->where('amount', '<', 0)->get();
        $vendorCurrency2 =   VendorCurrency::branch()->where('amount', '<', 0)->get();
        $shareholderCurrency =   ShareholderCurrency::branch()->get();
        $assets =   Assets::branch()->get();
        $stock_products =   StockSubProduct::branch()->where('available','>',0)->get();
        $products =   SubProduct::branch()->where('available','>',0)->get();

        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();

        //  $branch_base = $this->GetBranchTreasury($base_currency->currency_id); //
        $branch_base = $base_currency->currency_id; //
        if(!$branch_base || $branch_base == ''){
            return redirect()->back()->with('error', 'Please set your Base currency from the settings');
        }
        $treasury = Currency::all();

        $amount = 0;
        $client_amount = 0;
        $client_amount_deposit = 0;
        $vendor_amount_deposit = 0;
        $vendor_amount = 0;
        $main_stock_amount = 0;
        $shareholder_amount = 0;
        $all_stock_amount = 0;
        $assets_amount = 0;
        $rate = 1;
        $operation = 'multiply';
        foreach ($assets as $obj) {
            if ($obj->currency_id != $branch_base) {
                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $assets_amount += ($obj->quantity * $obj->asset_Value) * $rate;
                } else {
                    $assets_amount += ($obj->quantity * $obj->asset_Value) / $rate;
                }
            }
        }

        foreach ($shareholderCurrency as $obj) {
            if ($obj->currency_id != $branch_base) {
                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $shareholder_amount += $obj->amount * $rate;
                } else {
                    $shareholder_amount += $obj->amount / $rate;
                }
            }
        }
        $investment = ShareholderCurrency::where('currency_id', $branch_base)->where('branch_id', auth()->user()->branch_id)->sum('amount');
        $investment = $investment + $shareholder_amount;

        foreach ($account as $obj) {
            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $amount += $obj->amount * $rate;
                } else {
                    $amount += $obj->amount / $rate;
                }
            }
        }
        // dd($amount);
        foreach ($clientCurrency as $obj) {
            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $client_amount += $obj->amount * $rate;
                } else {
                    $client_amount += $obj->amount / $rate;
                }
            }
        }
        foreach ($clientCurrency2 as $obj) {
            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $client_amount_deposit += $obj->amount * $rate;
                } else {
                    $client_amount_deposit += $obj->amount / $rate;
                }
            }
        }
        foreach ($vendorCurrency as $obj) {
            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $vendor_amount += $obj->amount * $rate;
                } else {
                    $vendor_amount += $obj->amount / $rate;
                }
            }
        }
        foreach ($vendorCurrency2 as $obj) {
            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $obj->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $vendor_amount_deposit += $obj->amount * $rate;
                } else {
                    $vendor_amount_deposit += $obj->amount / $rate;
                }
            }
        }
        $main_stocks_amount = 0;
        foreach ($products as $obj) {
            $product=  Product::where('id',$obj->product_id)->first();

            if ($obj->currency_id != $branch_base) {

                $from = Rate::where('from_treasury', $product->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                $operation = isset($from->operation) ? $from->operation : 'multiply';
                $rate = isset($from->rate) ? $from->rate : 1;
                if ($operation == 'multiply') {
                    $main_stock_amount += ($obj->available * $obj->income_price) * $rate;
                } else {
                    $main_stock_amount += ($obj->available * $obj->income_price) / $rate;
                }
            }else{
                $main_stocks_amount += ($obj->available * $obj->income_price);
            }
        }
        $all_stock_total = 0;
        foreach ($stock_products as $obj) {
              $product=  SubProduct::with('product')->where('id',$obj->sub_product_id)->first();
            //   dd($product);
                if ($obj->currency_id != $branch_base) {

                    $from = Rate::where('from_treasury', $product->product->currency_id)->where('to_treasury', $branch_base)->latest()->branch()->first();
                    $operation = isset($from->operation) ? $from->operation : 'multiply';
                    $rate = isset($from->rate) ? $from->rate : 1;
                    if ($operation == 'multiply') {
                        $all_stock_amount += ($obj->available * $obj->income_price) * $rate;
                    } else {
                        $all_stock_amount += ($obj->available * $obj->income_price) / $rate;
                    }
                }else{
                    $all_stock_total += ($obj->available * $obj->income_price);
                }

        }

        $account_amount = Account::where('currency_id', $branch_base)->sum('amount');
        $client_currency_amount = ClientCurrency::where('currency_id', $branch_base)->where('amount','>',0)->sum('amount');
        $client_currency_amount_deposit = ClientCurrency::where('currency_id', $branch_base)->where('amount','<',0)->sum('amount');
        $vendor_currency_amount = VendorCurrency::where('currency_id', $branch_base)->where('amount','>',0)->sum('amount');
        $vendor_currency_amount_deposit = VendorCurrency::where('currency_id', $branch_base)->where('amount','<',0)->sum('amount');

        // $main_stocks_amount = Product::where('currency_id', $branch_base)
        //     ->select(DB::raw('SUM(quantity * cost) as total_amount'))
        //     ->value('total_amount');
        $assets_value = Assets::where('currency_id', $branch_base)
            ->select(DB::raw('SUM(quantity * assets_value) as total_amount'))
            ->value('total_amount');


            // foreach ($stock_products as $obj) {
            //     $product =  Product::where('id', $obj->product_id)->first();
            //     $all_stock_total += ($obj->quantity * $product->cost);
            // }

            $account_available = $account_amount + $amount;
            $client_receivable = $client_currency_amount + $client_amount;
            $client_depositable = $client_currency_amount_deposit + $client_amount_deposit;
            $main_stock_receivable = $main_stock_amount + $main_stocks_amount;
            $all_stock_receivable = $all_stock_total + $all_stock_amount;
            $vendor_receivable = $vendor_currency_amount + $vendor_amount;
            $vendor_depositable = $vendor_currency_amount_deposit + $vendor_amount_deposit;
            $total_asset = $assets_value + $assets_amount;

        // $unreceived = PurchaseDetail::branch()->whereColumn('received', '<', 'quantity')->get();
        // dd($unreceived);
        $unreceived = PurchaseDetail::branch()->whereColumn('received', '<', 'quantity')->get()
        ->sum(function ($unreceived_list){
            return ($unreceived_list->quantity - $unreceived_list->received) * $unreceived_list->cost;
        });

        return view('report.profit_lost_report', compact('account_available', 'client_receivable','client_depositable','main_stock_receivable','all_stock_receivable','vendor_receivable','vendor_depositable', 'investment','total_asset', 'unreceived'));
    }


    public function itemWiseSellReport(){
        $products = Product::branch()->get();
        return view('report.itemwise-sell-report', compact('products'));
    }

    public function getItemWiseSellReport(Request $request){
        $products = Product::branch()->get();

        $sell = SellDetail::branch()->with('sell', 'sell_sub_detail')->where('product_id', $request->product_id)->get();
        return view('report.itemwise-sell-report', compact('products', 'sell'));
    }


    public function itemWisePurchaseReport(){
        $products = Product::branch()->get();
        return view('report.itemwise-purchase-report', compact('products'));
    }

    public function getItemWisePurchaseReport(Request $request){
        $products = Product::branch()->get();

        $purchase = PurchaseDetail::branch()->with('purchase', 'purchase.vendor')->where('product_id', $request->product_id)->get();
        return view('report.itemwise-purchase-report', compact('products', 'purchase'));
    }
}
