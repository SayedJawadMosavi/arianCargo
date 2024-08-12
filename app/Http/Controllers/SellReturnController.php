<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellReturnRequest;
use App\Http\Requests\UpdateSellReturnRequest;
use App\Models\Account;
use App\Models\ClientCurrency;
use App\Models\ClientLog;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellDetail;
use App\Models\SellReturn;
use App\Models\StockProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

include "PersianCalendar.php";

class SellReturnController extends Controller
{
    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $returns = SellReturn::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = SellReturn::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->latest()->get();
        return view('sellreturn.index', compact('returns', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterSellReturn(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $returns = SellReturn::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = SellReturn::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('sellreturn.index', compact('returns', 'trashed'));
    }
    public function create()
    {
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $sells = Sell::branch()->get();
        return view('sellreturn.create', compact('sells', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSellReturnRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellReturnRequest $request)
    {
        DB::beginTransaction();
        try {
            $flag = false;
            $sell = Sell::find($request->sell_id);
            $sell_detail = SellDetail::find($request->product_id);
            if ($sell_detail < $request->quantity) {
                throw new \Exception('مقدار واپسی بیشتر از مقدار فروش است.');
            }
            $product = StockProduct::find($sell_detail->product_id);
            $total = $request->quantity * $sell_detail->cost;

            $sellreturn = SellReturn::create([
                'sell_id' => $request->sell_id,
                'sell_detail_id' => $sell_detail->id,
                'stock_product_id' => $product->id,
                'quantity' => $request->quantity,
                'cost' => $sell_detail->cost,
                'total' => $total,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            if ($sellreturn) {
                $product->increment('quantity', $request->quantity);
                $sell_detail->decrement('quantity', $request->quantity);
                $sell_detail->decrement('total', $total);
                $sell->decrement('total', $total);
                $sell->decrement('balance', $total);
                $log_withdraw = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_withdraw->decrement('amount', $total);
                $log_deposit = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_deposit->decrement('available', $total);

                // ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'deposit'])->decrement('mount', $total);
                $flag = ClientCurrency::find($log_deposit->client_currency_id)->decrement('amount', $total);
            }
            if ($flag) {
                DB::commit();
                return redirect()->route('sellreturn.index')->with('success', 'Sell return stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('sellreturn.index')->with('error', 'Sell Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Sell: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellReturn  $sellReturn
     * @return \Illuminate\Http\Response
     */
    public function show(SellReturn $sellReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellReturn  $sellReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $return =  SellReturn::find($id);
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        $sells = Sell::branch()->get();

        return view('sellreturn.create', compact('sells', 'accounts', 'return'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellReturnRequest  $request
     * @param  \App\Models\SellReturn  $sellReturn
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellReturnRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $sell_return= SellReturn::find($id);

            $sell = Sell::find($request->sell_id);
            $sell_detail = SellDetail::find($request->product_id);
            if ($sell_detail < $request->quantity) {
                throw new \Exception('مقدار واپسی بیشتر از مقدار فروش است.');
            }
            $product = StockProduct::find($sell_detail->product_id);
            $total = $request->quantity * $sell_detail->cost;



            if ($product) {
                $product->decrement('quantity', $sell_return->quantity);
                $product->increment('quantity', $request->quantity);

                $sell_detail->increment('quantity', $sell_return->quantity);
                $sell_detail->decrement('quantity', $request->quantity);

                $sell_detail->increment('total', $sell_return->total);
                $sell_detail->decrement('total', $total);

                $sell->increment('total', $sell_return->total);
                $sell->decrement('total', $total);

                $sell->increment('balance', $sell_return->total);
                $sell->decrement('balance', $total);

                $log_withdraw = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_withdraw->increment('amount', $sell_return->total);

                $log_withdraw = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_withdraw->decrement('amount', $total);

                $log_deposit = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_deposit->increment('available', $sell_return->total);

                $log_deposit = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->first();
                $log_deposit->decrement('available', $total);

                // ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'deposit'])->decrement('mount', $total);
                $flag = ClientCurrency::find($log_deposit->client_currency_id)->increment('amount', $sell_return->total);
                $flag = ClientCurrency::find($log_deposit->client_currency_id)->decrement('amount', $total);
                $update = $sell_return->update([
                    'sell_id' => $request->sell_id,
                    'sell_detail_id' => $sell_detail->id,
                    'stock_product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'cost' => $sell_detail->cost,
                    'total' => $total,
                    'description' => $request->description,
                    'miladi_date' => $request->miladi_date,
                    'shamsi_date' => $request->shamsi_date,
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);
            }
            if ($update) {
                DB::commit();
                return redirect()->route('sellreturn.index')->with('success', 'Sell return update successfully');
            } else {
                DB::rollBack();
                return redirect()->route('sellreturn.index')->with('error', 'Sell Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Sell: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellReturn  $sellReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $sell_return = SellReturn::find($id);
        if ($sell_return) {
            $product = StockProduct::find($sell_return->stock_product_id)->decrement('quantity', $sell_return->quantity);
            $details = SellDetail::where('id', $sell_return->sell_detail_id)->increment('quantity', $sell_return->quantity);
            $details = SellDetail::where('id', $sell_return->sell_detail_id)->increment('total', $sell_return->total);
            $sell = Sell::where('id', $sell_return->sell_id)->increment('total', $sell_return->total);
            $sell = Sell::where('id', $sell_return->sell_id)->increment('balance', $sell_return->total);
            $sell_currency = Sell::where('id', $sell_return->sell_id)->first();
        }
        $flag = ClientCurrency::where('currency_id', $sell_currency->currency_id)->where('client_id', $sell_currency->client_id)->increment('amount', $sell_return->total);
        if ($flag) {
            $sell_return->delete();
            DB::commit();
            return redirect()->route('sellreturn.index')->with('success', 'Sell return deleted successfully');
        } else {
            DB::rollBack();
            return redirect()->route('sellreturn.index')->with('error', 'Sell Failed');
        }
    }

    function getData($id)
    {
        $data = '';
        $assets = SellDetail::where('sell_id', $id)->get();
        $data .= '<option disabled selected>' . __('home.please_select') . ' </option>';
        foreach ($assets as $d) {
            $data .= '<option value="' . $d->id . '" data-qty = "' . $d->quantity . '" data-cost = "' . $d->cost . '">' . $d->product->name . ' (' . $d->quantity . ') ' . $d->stock_product->stock->name . '</option>';
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
