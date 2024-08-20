<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellRequest;
use App\Http\Requests\UpdateSellRequest;
use App\Http\Traits\AccountLogTrait;
use App\Http\Traits\ClientLogTrait;
use App\Http\Traits\CurrencyTrait;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Client;
use App\Models\ClientCurrency;
use App\Models\ClientLog;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellDetail;
use App\Models\SellSubDetail;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockSubProduct;
use App\Models\SubProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class SellController extends Controller
{
    use ClientLogTrait, CurrencyTrait, AccountLogTrait;

    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:sell.create',['only' => ['create', 'store']]);
        $this->middleware('permission:sell.edit',['only' => ['edit', 'update']]);
        $this->middleware('permission:sell.view',['only' => ['index']]);
        $this->middleware('permission:sell.delete',['only' => ['destroy']]);
        $this->middleware('permission:sell.restore',['only' => ['restore']]);
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
        $sells = Sell::branch()->with('currency')->whereBetween($column, [$from, $to])->latest()->get();


        $trashed = Sell::branch()->with('currency')->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('sell.index', compact('sells', 'trashed'));
    }

    public function filterSell(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $sells = Sell::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Sell::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('sell.index', compact('sells', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::branch()->get();
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        // $products = Product::where('quantity', '>', 0)->branch()->get();
        $products = SubProduct::branch()
        ->selectRaw('product_id, sum(available) as total_quantity')
        ->groupBy('product_id')
        ->get();

        $stocks = Stock::branch()->get();
        $currencies = Currency::active()->branch()->get();
        return view('sell.create', compact('clients', 'accounts', 'products', 'stocks','currencies'));
    }
    public function getProducts($id)
    {
        try {

            // $stockProducts = StockProduct::where('stock_id', $id)->with('product')->get();

            $stockProducts = StockProduct::with('subProducts', 'product')->where('stock_id', $id)
            ->whereHas('subProducts', function ($query) {
                $query->where('available', '>', 0);
            })
            ->get();

            // dd($stockProducts);
            $currency = StockProduct::where('stock_id', $id)->with('product.currency')->first();

            return response()->json(['products' => $stockProducts,'currency'  =>$currency]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function getClientCurrencyId($id)
    {
        try {
            $currency = Account::where('id', $id)->with('currency')->first();
            return response()->json(['data' => $currency]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
    public function getClientCurrencyData($id)
    {

        try {
            $currency = ClientCurrency::where('client_id', $id)->with('currency','client')->first();


            $data=Account::with('currency')->Where('currency_id',$currency->currency_id)->get();

            return response()->json(['data' => $data,'client'  =>$currency]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
    public function getProductCurrencyId($id)
    {
        try {
            $currency = StockProduct::where('id', $id)->with('product','product.currency', 'subProducts')->first();
            return response()->json(['data' => $currency]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSellRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellRequest $request)
    {

        DB::beginTransaction();
        try {
            $balance = $request->total - $request->paid;
            $account = Account::find($request->account_id);

            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            $branch_base = $base_currency->currency_id; //

            $sell = Sell::create([
                'client_id' => $request->client_id,
                'account_id' => $request->account_id,
                'client_name' => $request->client_name,
                'currency_id' => $account->currency_id,
                'total' => $request->total,
                'total_cbm' => $request->total_cbm,
                'paid' => $request->paid,
                'balance' => $balance,
                'rate' => $request->rate,
                'operation' => $request->operation,
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            $product = $request->product;
            $cost = $request->cost;
            $quantity = $request->quantity;
            $purchase = $request->purchase;
            $cbm = $request->cbm;

            $to_currency_id = $request->to_currency_id;

            // $description = 'درک فروش: ' . $sell->id . ' - ' . $request->description;
            $description = $request->description;
            // dd($product[0]);

            foreach ($product as $index => $item) {

                if(StockProduct::find($product[$index])->subProducts->sum('available') < $quantity[$index]){
                    throw new \Exception('مقدار کافی موجود نیست.');
                }
                $profit = 0;

                $stock_product =  StockProduct::find($product[$index]);
                $remainingQuantity = $quantity[$index];
                // Retrieve sub-products for the given product ID, ordered by creation date (FIFO)
                $subProducts = StockSubProduct::where('stock_product_id', $stock_product->id)
                    ->where('available', '>', 0)
                    ->orderBy('created_at')
                    ->get();
                    // dd($subProducts);
                // dd($stock_product->product->income_price);
                $profit = 0;
                $sell_detail = SellDetail::create([
                    'sell_id' => $sell->id,
                    'product_id' => $stock_product->product_id,
                    'stock_product_id' => $product[$index],
                    'quantity' => $quantity[$index],
                    'cbm' => $cbm[$index],
                    'cost' => $cost[$index],
                    'rate' => $request->rate,
                    'total' => $quantity[$index] * $cost[$index],
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);

                // Loop through sub-products and allocate quantity based on FIFO
                foreach ($subProducts as $subProduct) {
                    if ($remainingQuantity <= 0) {
                        break; // All quantity allocated
                    }
                    // Calculate how much quantity can be allocated from this sub-product
                    $quantityToAllocate = min($remainingQuantity, $subProduct->available);

                    // Update the sub-product quantity
                    $subProduct->available -= $quantityToAllocate;
                    $subProduct->save();

                    // Decrease remaining quantity to allocate
                    $remainingQuantity -= $quantityToAllocate;
                    // $profit = $request->quantity * ($cost[$index] - $subProduct->income_price);
                    // echo 'income: '. $subProduct->income_price;
                    // echo '<br>quantityToAllocate: '. $quantityToAllocate;
                    // dd($profit);
                    // Store allocated quantity in StockSubProduct
                    $SellSubDetail = SellSubDetail::create([
                        'sell_detail_id' => $sell_detail->id,
                        'stock_sub_product_id' => $subProduct->id,
                        'quantity' => $quantityToAllocate,
                        'income_price' => $subProduct->income_price,
                        'cost' => $cost[$index],
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);
                    // dd($transfer_detail);
                    if($branch_base == $account->currency_id){
                        $profit += ($quantityToAllocate * $cost[$index]) - ($quantityToAllocate * $subProduct->income_price);
                    }else{
                        if($request->operation == 'multiply'){
                            $new_cost = $cost[$index] * $request->rate;
                            $profit += ($quantityToAllocate * $new_cost) - ($quantityToAllocate * $subProduct->income_price);
                        }else{
                            $new_cost = $cost[$index] / $request->rate;
                            $profit += ($quantityToAllocate * $new_cost) - ($quantityToAllocate * $subProduct->income_price);
                        }
                    }
                }
                $sell_detail->update([
                    'profit' => $profit,
                ]);
            }

            $type = 'deposit';
            // $currentDate = isset($request->date) ? $request->date :date('Y-m-d');
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            // Account::find($request->account_id)->increment('amount', $request->paid);
            if ($account->amount !== null) {
                // If amount is not null, add $request->paid to the existing amount
                $account->increment('amount', (float) $request->paid);
            } else {
                // If amount is null, set it to the value of $request->paid
                $account->update(['amount' => (float) $request->paid]);
            }

            // dd($account);

            $flag = $this->InsertAccountLog($request->account_id, $type, $request->paid, $description, $account->amount, 'sell', $sell->id, $currentDate);
            // dd($flag);

            $curr = $this->GetClientCurrency($request->client_id, $account->currency_id, 0);
            $curr->decrement('amount', $request->total);

            $type = 'withdraw';
            $this->InsertClientLog($request->client_id, $curr->id, $type, $request->total, $description, $curr->amount, 'sell', $sell->id, $currentDate);

            $curr->increment('amount', $request->paid);
            $this->InsertClientLog($request->client_id, $curr->id, 'deposit', $request->paid, $description, $curr->amount, 'sell', $sell->id, $currentDate);


            if ($flag) {
                DB::commit();
                return redirect()->route('sell.index')->with('success', 'Sell stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('sell.index')->with('error', 'Sell Failed');
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
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function show(Sell $sell)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function edit(Sell $sell)
    {
        $sell_client = Client::find($sell->client_id);
        $clients = Client::branch()->get();
        $accounts = Account::branch()->where('currency_id', $sell_client->currency->currency_id)->orderBy('default', 'DESC')->get();
        $products = Product::branch()->get();
        $stocks = Stock::get();
        $currencies = Currency::active()->get();

        return view('sell.create', compact('clients', 'accounts', 'products', 'sell','stocks', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellRequest  $request
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellRequest $request, Sell $sell)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());

            Account::find($sell->account_id)->decrement('amount', $sell->paid);
            AccountLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'deposit'])->delete();

            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            Account::find($request->account_id)->increment('amount', $request->paid);
            $account = Account::find($request->account_id);

            $flag = $this->InsertAccountLog($request->account_id, 'deposit', $request->paid, $sell->description, $account->amount, 'sell', $sell->id, $currentDate);

            $log = ClientLog::where(['action_id' => $sell->id, 'action' => 'sell'])->first();

            // dd($log);
            ClientCurrency::find($log->client_currency_id)->increment('amount', $sell->total - $sell->paid);
            ClientLog::where(['action_id' => $sell->id, 'action' => 'sell'])->delete();


            $balance = $request->total - $request->paid;
            $flag = $sell->update([
                'client_id' => $request->client_id,
                'account_id' => $request->account_id,
                'total' => $request->total,
                'total_cbm' => $request->total_cbm,
                'paid' => $request->paid,
                'balance' => $balance,
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,
            ]);


            // NEW CLIENT CURRENCY AND LOG
            $curr = $this->GetClientCurrency($request->client_id, $account->currency_id, 0);
            $curr->decrement('amount', $request->total);

            $type = 'withdraw';
            $this->InsertClientLog($request->client_id, $curr->id, $type, $request->total, $request->description, $curr->amount, 'sell', $sell->id, $currentDate);

            $curr->increment('amount', $request->paid);
            $this->InsertClientLog($request->client_id, $curr->id, 'deposit', $request->paid, $request->description, $curr->amount, 'sell', $sell->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('sell.index')->with('success', 'sell updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('sell.index')->with('error', 'sell update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating sell: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sell $sell)
    {
        //
    }

    public function getSellDetail(Sell $sell, $id)
    {
        $sells = Sell::find($id);
        $details = SellDetail::with('sell_sub_detail')->where('sell_id', $id)->get();
        // $products = Product::branch()->sub_productwhere('quantity', '>', 0)->get();
        $stocks = Stock::get();
        return view('sell.detail', compact('sells', 'details', 'stocks'));
    }

    public function sellDetailDelete(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $detail = SellDetail::find($id);
            Product::find($detail->product_id)->increment('quantity', $detail->quantity);
            $total = $detail->quantity * $detail->cost;

            $sell = Sell::find($detail->sell_id);
            $sell->decrement('total', $total);
            $sell->decrement('balance', $total);

            // Account::find($sell->account_id)->decrement('amount', $total);
            // $account_log = AccountLog::where([
            //     'action' => 'sell',
            //     'action_id' => $sell->id,
            //     'account_id' => $sell->account_id,
            // ])->first();
            // $account_log->decrement('amount', $total);


            $client_log = ClientLog::where(['action' => 'sell', 'action_id' => $sell->id])->first();
            $new_curr = ClientCurrency::find($client_log->client_currency_id);
            $new_curr->increment('amount', $total);

            $log = ClientLog::where(['action' => 'sell', 'action_id' => $sell->id, 'type' => 'withdraw'])->first();
            $log->decrement('amount', $total);
            $flag = $log->update([
                'available' => $new_curr->amount,
            ]);

            $log_received = ClientLog::where(['action' => 'sell', 'action_id' => $sell->id, 'type' => 'deposit'])->first();
            $flag = $log_received->update([
                'available' => $new_curr->amount - $sell->paid,
            ]);


            $detail->update([
                'deleted_by' =>auth()->user()->id,
            ]);
            $detail->delete();
            if ($flag) {
                DB::commit();
                return redirect()->back()->with('success', 'Item deleted successfully');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Delete failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error deleting sell item: ' . $e->getMessage());
        }
    }

    public function sellDetailUpdate(Request $request)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $old = SellDetail::find($request->id);
            dd($old);
            // dd('yes');
            StockProduct::where('id',$old->stock_product_id)->increment('quantity', $old->quantity);
            StockProduct::where('id',$old->stock_product_id)->decrement('quantity', $request->quantity);

            $total = $old->quantity * $old->cost;
            $new_total = $request->quantity * $request->cost;

            $sell = sell::find($old->sell_id);
            $sell->decrement('total', $total);
            $sell->increment('total', $new_total);
            $sell->update(['balance' => $sell->total - $sell->paid]);

            // echo '</Br>old_qty: '.$old->quantity;
            $old->update(['quantity' => $request->quantity, 'cost' => $request->cost, 'total' => $new_total]);

            $client_log = ClientLog::where(['action' => 'sell', 'action_id' => $sell->id])->first();

            // echo '</Br>total: '.$total;
            // echo 'new_total: '.$new_total;
            // dd($new_total);
            ClientCurrency::find($client_log->client_currency_id)->decrement('amount', $new_total - $total);
            $new_curr = ClientCurrency::find($client_log->client_currency_id);

            $log = ClientLog::where(['action' => 'sell', 'action_id' => $sell->id, 'type' => 'withdraw'])->first();
            // echo '</br>v cur am: '.$new_curr->amount;
            // dd($log);
            $flag = $log->update([
                'amount' => $sell->total,
                'available' => $new_curr->amount,
            ]);

            // dd($flag);
            if ($flag) {
                DB::commit();
                return  response()->json(['success', ' محصول ویرایش گردید']);
            } else {
                DB::rollBack();
                return  response()->json(['success', ' خطا در ویرایش ']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating sell: ' . $e->getMessage());
        }
    }

    public function sellDetailInsert(Request $request, $id)
    {

        $flag = false;
        DB::beginTransaction();
        try {

            $product = $request->product;
            $cost = $request->cost;
            $quantity = $request->quantity;
            $purchase = $request->purchase;
            $cbm = $request->cbm;
            $to_currency_id = $request->to_currency_id;


            foreach ($request->product as $index => $item) {


                $stock_product =  StockProduct::find($product[$index]);
                if ($to_currency_id[$index]   != $request->currency_id) {

                    $rate= $request->rate;
                  }else{
                      $rate=1;
                  }
                SellDetail::create([
                    'sell_id' => $id,
                    'product_id' => $stock_product->product_id,
                    'stock_product_id' => $product[$index],
                    'income_price' => $purchase[$index],
                    'quantity' => $quantity[$index],
                    'cost' => $cost[$index],
                    'rate' => $rate,
                    'cbm' => $cbm[$index],
                    'total' => $quantity[$index] * $cost[$index],
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);

                $prod = StockProduct::find($product[$index]);
                if ($prod->quantity < $quantity[$index]) {
                    throw new \Exception('Available quantity is less than the required quantity for: ' . $prod->name);
                }
                $prod->decrement('quantity', $quantity[$index]);
            }
            $sell = Sell::find($id);
            $sell->increment('total', $request->total);
            $flag = $sell->increment('balance', $request->total);

            // $currentDate = isset($request->date) ? $request->date :date('Y-m-d');
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            $curr = $this->GetClientCurrency($sell->client_id, $sell->account->currency_id, null);

            $curr->decrement('amount', $request->total);
            ClientLog::where(['action_id' => $sell->id, 'action' => 'sell', 'type' => 'withdraw'])->delete();

            $type = 'withdraw';
            $this->InsertClientLog($sell->client_id, $curr->id, $type, $sell->total, $sell->description, $curr->amount, 'sell', $sell->id, $currentDate);

            // dd($request->paid);
            // $curr->increment('amount', $request->paid);
            // $this->InsertClientLog($sell->client_id, $curr->id, 'deposit', $sell->paid, $sell->description, $curr->amount, 'sell', $sell->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('sell.index')->with('success', 'sell stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('sell.index')->with('error', 'sell Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating sell: ' . $e->getMessage());
        }
    }


    public function bill($id)
    {
        $sell = Sell::find($id);
        return view('sell.bill', compact('sell'));
    }
}
