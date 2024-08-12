<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Traits\AccountLogTrait;
use App\Http\Traits\CurrencyTrait;
use App\Http\Traits\VendorLogTrait;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Receive;
use App\Models\Setting;
use App\Models\SubProduct;
use App\Models\Vendor;
use App\Models\VendorCurrency;
use App\Models\VendorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class PurchaseController extends Controller
{
    use VendorLogTrait, CurrencyTrait;

    protected $settings;
    public function __construct(Request $request)
    {
        $this->middleware('permission:purchase.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:purchase.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase.delete', ['only' => ['destroy']]);
        $this->middleware('permission:purchase.restore', ['only' => ['restore']]);
        $this->middleware('permission:purchase.forceDelete', ['only' => ['forceDelete']]);
        $this->middleware('permission:purchase.details', ['only' => ['getPurchaseDetail', 'purchaseDetailDelete', 'purchaseDetailUpdate', 'purchaseDetailInsert']]);
        $this->settings = $request->get('settings');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use AccountLogTrait;

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
        $purchases = Purchase::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Purchase::branch()->whereBetween($column, [$from, $to])->onlyTrashed()->get();
        return view('purchase.index', compact('purchases', 'trashed'));
    }


    public function filterPurchase(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $purchases = Purchase::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Purchase::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('purchase.index', compact('purchases', 'trashed'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        $branch_base = $base_currency->currency_id; //

        $accounts = Account::branch()->where('currency_id', $branch_base)->orderBy('default', 'DESC')->get();
        $vendors = Vendor::branch()->get();
        $products = Product::branch()->get();
        $setting = Setting::branch()->get();
        return view('purchase.create', compact('vendors', 'accounts', 'products', 'setting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {

        DB::beginTransaction();
        try {
            $balance = $request->total - $request->paid;
            $flag = false;
            $purchase = Purchase::create([
                'vendor_id' => $request->vendor_id,
                'account_id' => $request->account_id,
                'total' => $request->total,
                'paid' => $request->paid,
                'balance' => $balance,
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
            $product = $request->product;
            $cost = $request->cost;
            $expense = $request->expense;
            $sell = $request->sell;
            $quantity = $request->quantity;

            // $description = 'درک خریداری: ' . $purchase->id . ' - ' . $request->description;
            $description = $request->description;

            $account = Account::find($request->account_id);
            if ($account->amount < $request->paid) {
                throw new \Exception('Insufficient account balance');
            }
            foreach ($request->product as $index => $item) {
                if ($quantity[$index] > 0) {

                    PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $product[$index],
                        'quantity' => $quantity[$index],
                        'received' => 0,
                        'cost' => $cost[$index],
                        // 'tax' => 0,
                        // 'rent' => 0,
                        // 'other' => $expense[$index],
                        // 'sell_price' => $sell[$index],
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);
                    // $prod = Product::find($product[$index]);
                    // $prod->update([
                    //     'currency_id' => $account->currency_id,
                    //     'cost' => $cost[$index],
                    //     'expense' => $expense[$index],
                    //     'sell' => $sell[$index],
                    // ]);
                    // $prod->increment('quantity', $quantity[$index]);
                }
            }

            $type = 'withdraw';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            if($request->paid > 0){

                if ($account->amount !== null) {
                    // If amount is not null, add $request->paid to the existing amount
                    $account->decrement('amount', (float) $request->paid);
                } else {
                    // If amount is null, set it to the value of $request->paid
                    $paid = 0 - $request->paid;
                    $account->update(['amount' => (float) $paid]);
                }

                $flag = $this->InsertAccountLog($request->account_id, $type, $request->paid, $description, $account->amount, 'purchase', $purchase->id, $currentDate);
            }
            // dd($flag);

            $curr = $this->GetVendorCurrency($request->vendor_id, $account->currency_id, 0);
            $curr->increment('amount', $request->total);

            $type = 'paid';
            $this->InsertVendorLog($request->vendor_id, $curr->id, $type, $request->total, $description, $curr->amount, 'purchase', $purchase->id, $currentDate);

            $curr->decrement('amount', $request->paid);
            $flag = $this->InsertVendorLog($request->vendor_id, $curr->id, 'received', $request->paid, $description, $curr->amount, 'purchase', $purchase->id, $currentDate);


            if ($flag) {
                DB::commit();
                return redirect()->route('purchase.index')->with('success', 'Purchase stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('purchase.index')->with('error', 'Purchase Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating purchase: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $vendors = Vendor::branch()->get();
        $accounts = Account::branch()->where('currency_id', $purchase->account->currency_id)->orderBy('default', 'DESC')->get();
        $products = Product::branch()->get();
        return view('purchase.create', compact('vendors', 'accounts', 'products', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {

        DB::beginTransaction();
        try {
            // dd($request->all());

            Account::find($purchase->account_id)->increment('amount', $purchase->paid);
            AccountLog::where(['action_id' => $purchase->id, 'action' => 'purchase', 'type' => 'withdraw'])->delete();


            $currentDate = isset($request->miladi_date) ? $request->miladi_date : $request->shamsi_date;

            Account::find($request->account_id)->decrement('amount', $request->paid);
            $account = Account::find($request->account_id);

            $flag = $this->InsertAccountLog($request->account_id, 'withdraw', $request->paid, $purchase->description, $account->amount, 'purchase', $purchase->id, $currentDate);


            $log = VendorLog::where(['action_id' => $purchase->id, 'action' => 'purchase'])->first();

            VendorCurrency::find($log->vendor_currency_id)->decrement('amount', $purchase->total - $purchase->paid);
            VendorLog::where(['action_id' => $purchase->id, 'action' => 'purchase'])->delete();
            // echo $purchase->total - $purchase->paid;
            // dd(VendorCurrency::find($log->vendor_currency_id));


            $balance = $request->total - $request->paid;
            $flag = $purchase->update([
                'vendor_id' => $request->vendor_id,
                'account_id' => $request->account_id,
                'total' => $request->total,
                'paid' => $request->paid,
                'balance' => $balance,
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,
            ]);


            // NEW VENDOR CURRENCY AND LOG
            $curr = $this->GetVendorCurrency($request->vendor_id, $account->currency_id, 0);
            $curr->increment('amount', $request->total);

            $type = 'paid';
            $this->InsertVendorLog($request->vendor_id, $curr->id, $type, $request->total, $request->description, $curr->amount, 'purchase', $purchase->id, $currentDate);

            $curr->decrement('amount', $request->paid);
            $this->InsertVendorLog($request->vendor_id, $curr->id, 'received', $request->paid, $request->description, $curr->amount, 'purchase', $purchase->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('purchase.index')->with('error', 'Purchase update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        // dd($purchase);
        $flag = false;
        DB::beginTransaction();
        try {

            if($purchase->receive->count()){
                throw new \Exception('Purchase already received, therefore cannot be deleted.');
            }
            $details = PurchaseDetail::where('purchase_id', $purchase->id)->get();
            // dd($details);
            foreach ($details as $obj) {
                $obj->update(['deleted_by' => auth()->user()->id, 'deleted_at' => Carbon::now()]);
                $obj->delete();
            }
            $purchase->update(['deleted_by' => auth()->user()->id, 'deleted_at' => Carbon::now()]);
            // dd('here');
            Account::find($purchase->account_id)->decrement('amount', (float) $obj->paid);
            AccountLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->update(['deleted_by' => auth()->user()->id, 'deleted_at' => Carbon::now()]);
            AccountLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->delete();

            foreach (VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->get() as $log) {
                $log->update(['deleted_by' => auth()->user()->id, 'deleted_at' => Carbon::now()]);
                $vendor_currency_id = $log->vendor_currency_id;
            }

            VendorCurrency::where('id', $vendor_currency_id)->decrement('amount', $purchase->total - $purchase->paid);
            VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->delete();
            $flag = $purchase->delete();

            if ($flag) {
                DB::commit();
                return redirect()->back()->with('success', 'Purchase deleted successfully');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Delete failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error deleting purchase item: ' . $e->getMessage());
        }
    }

    public function getPurchaseDetail(Purchase $purchase, $id)
    {
        $purchases = Purchase::find($id);
        $details = PurchaseDetail::where('purchase_id', $id)->get();
        $products = Product::branch()->get();

        return view('purchase.detail', compact('purchases', 'details', 'products'));
    }

    public function getPurchaseReceive(Purchase $purchase, $id)
    {
        $purchases = Purchase::find($id);
        // $products = PurchaseDetail::where('purchase_id', $id)->where('received', '<', 'quantity')->toSql();
        $products = PurchaseDetail::where('purchase_id', $id)
        ->where(function ($query) {
            $query->where('received', '<', DB::raw('quantity'))
                ->orWhereNull('received');
        })
        ->get();

        $details = PurchaseDetail::where('purchase_id', $id)->first();
        $receieves = Receive::with('product')->where('purchase_id', $purchases->id)->get();


        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        $branch_base = $base_currency->currency_id; //

        $accounts = Account::branch()->where('currency_id', $branch_base)->orderBy('default', 'DESC')->get();

        return view('purchase.receive', compact('purchases', 'products', 'accounts', 'receieves'));
    }

    public function purchaseDetailDelete(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $detail = PurchaseDetail::find($id);
            Product::find($detail->product_id)->decrement('quantity', $detail->quantity);
            $total = $detail->quantity * ($detail->cost + $detail->rent + $detail->other);

            $purchase = Purchase::find($detail->purchase_id);
            $purchase->decrement('total', $total);
            $purchase->decrement('balance', $total);

            // Account::find($purchase->account_id)->increment('amount', $total);
            // $account_log = AccountLog::where([
            //     'action' => 'purchase',
            //     'action_id' => $purchase->id,
            //     'account_id' => $purchase->account_id,
            // ])->first();
            // $account_log->increment('amount', $total);


            $vendor_log = VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->first();
            $new_curr = VendorCurrency::find($vendor_log->vendor_currency_id);
            $new_curr->decrement('amount', $total);

            $log = VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id, 'type' => 'paid'])->first();
            $log->decrement('amount', $total);
            $flag = $log->update([
                'available' => $new_curr->amount,
            ]);

            $log_received = VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id, 'type' => 'received'])->first();
            $flag = $log_received->update([
                'available' => $new_curr->amount - $purchase->paid,
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
            return redirect()->back()->with('error', 'Error deleting purchase item: ' . $e->getMessage());
        }
    }
    // public function purchaseReceiveDelete(Request $request, $id){

    //     DB::beginTransaction();
    //     try {

    //         $flag = false;
    //         $receive = Receive::find($id);
    //         Product::find($receive->product_id)->decrement('quantity', $receive->quantity);
    //         $total = $receive->quantity * ($receive->expense + $receive->rent);

    //         $purchase_detail = PurchaseDetail::find($receive->purchase_detail_id);
    //         $purchase_detail->decrement('received', $receive->quantity);
    //         $purchase = Purchase::find($purchase_detail->purchase_id);

    //         $account_log = AccountLog::where([
    //             'action' => 'received',
    //             'action_id' => $receive->id,
    //             'account_id' => $purchase->account_id,
    //         ])->first();
    //         Account::find($purchase->account_id)->increment('amount', $account_log->amount);
    //         $account_log->delete();
    //         $receive->delete();
    //         if($flag){
    //             DB::commit();
    //             return redirect()->back()->with('success', 'Item deleted successfully');
    //         }else{
    //             DB::rollBack();
    //             return redirect()->back()->with('error', 'Delete failed');
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         // Handle the exception
    //         return redirect()->back()->with('error', 'Error deleting purchase item: ' . $e->getMessage());
    //     }

    // }
    public function purchaseDetailUpdate(Request $request)
    {

        DB::beginTransaction();

        try {
            $flag = false;
            $old = PurchaseDetail::find($request->id);

            Product::find($old->product_id)->decrement('quantity', $old->quantity);
            Product::find($old->product_id)->increment('quantity', $request->quantity);

            $total = $old->quantity * ($old->cost + $old->rent + $old->other);
            $new_total = $request->quantity * ($request->cost + $request->rent + $request->other);

            $prod = Product::find($old->product_id);
            $prod->update(['cost' => $request->cost]);

            $purchase = Purchase::find($old->purchase_id);
            $purchase->decrement('total', $total);
            $purchase->increment('total', $new_total);
            $purchase->update(['balance' => $purchase->total - $purchase->paid]);

            // echo '</Br>old_qty: '.$old->quantity;
            $old->update(['quantity' => $request->quantity, 'cost' => $request->cost]);

            $vendor_log = VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id])->first();

            // echo '</Br>total: '.$total;
            // echo 'new_total: '.$new_total;
            // dd($new_total);
            VendorCurrency::find($vendor_log->vendor_currency_id)->increment('amount', $new_total - $total);
            $new_curr = VendorCurrency::find($vendor_log->vendor_currency_id);

            $log = VendorLog::where(['action' => 'purchase', 'action_id' => $purchase->id, 'type' => 'paid'])->first();
            // echo '</br>v cur am: '.$new_curr->amount;
            // dd($log);
            $flag = $log->update([
                'amount' => $purchase->total,
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
            return redirect()->back()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }
    public function purchaseReceiveUpdate(Request $request)
    {

        DB::beginTransaction();
        try {
            $flag = false;
            $old = Receive::find($request->id);
            $purchase_detail = PurchaseDetail::find($old->purchase_detail_id);

            // dd($purchase_detail);
            if($request->quantity > $purchase_detail->quantity) {
                return  response()->json(['error', 'Receive quantity cannot be greater than purchased quantity']);
            }
            // return $old->quantity;
            SubProduct::where('receive_id', $old->id)->decrement('quantity', $old->quantity);
            SubProduct::where('receive_id', $old->id)->increment('quantity', $request->quantity);

            $total = $old->quantity * ($old->expense + $old->rent);
            $new_total = $request->quantity * ($request->expense + $request->rent);



            $purchase = Purchase::find($purchase_detail->purchase_id);

            $purchase_detail->update(['received'  =>(($purchase_detail->received- $old->quantity) + $request->quantity)]);
            // $purchase_detail->increment('received', $new_total);

            $account = Account::find($purchase->account_id);
            $account->increment('amount', $total);
            $account->decrement('amount', $new_total);
            $account_log = AccountLog::where([
                'action' => 'received',
                'action_id' => $old->no,
                'account_id' => $purchase->account_id,
                ])->first();
                // dd($old->no);
                // return $total;
                $account_log->decrement('amount', $total);
                $account_log->increment('amount', $new_total);
                $account_log->increment('balance', $total);
                $account_log->decrement('balance', $new_total);

           $flag= $old->update(['quantity' => $request->quantity, 'expense' => $request->expense,'rent' =>$request->rent]);


            if ($flag) {
                DB::commit();
                return  response()->json(['success', ' Receive Updated SuccessFully']);
            } else {
                DB::rollBack();
                return  response()->json(['success', '   error in edit ']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }
    public function getProductReceived($id)
    {

        try {
            $products = PurchaseDetail::where('id', $id)->with('product')->first();
            return response()->json(['data' => $products]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
    public function purchaseDetailInsert(Request $request, $id)
    {

        $flag = false;
        DB::beginTransaction();
        try {

            $product = $request->product;
            $cost = $request->cost;
            $expense = $request->expense;
            $quantity = $request->quantity;
            $sell = $request->sell;
            $purchase = Purchase::find($id);
            foreach ($request->product as $index => $item) {
                // $sell = $cost[$index] + $expense[$index];
                $detail = PurchaseDetail::create([
                    'purchase_id' => $id,
                    'product_id' => $product[$index],
                    'quantity' => $quantity[$index],
                    'cost' => $cost[$index],
                    'tax' => 0,
                    'rent' => 0,
                    'other' => $expense[$index],
                    'sell_price' => $sell[$index],
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);

                $prod = Product::find($product[$index]);
                if ($detail) {
                    $prod->update([
                        'currency_id' => $purchase->account->currency_id,
                        'cost' => $cost[$index],
                        'expense' => $expense[$index],
                    ]);
                    $flag = $prod->increment('quantity', $quantity[$index]);
                }
            }

            $purchase = Purchase::find($id);
            $purchase->increment('total', $request->total);
            $flag = $purchase->increment('balance', $request->total);

            $currentDate = isset($request->miladi_date) ? $request->miladi_date : $request->shamsi_date;

            $curr = $this->GetVendorCurrency($purchase->vendor_id, $purchase->account->currency_id, null);

            $curr->increment('amount', $request->total);
            VendorLog::where(['action_id' => $purchase->id, 'action' => 'purchase', 'type' => 'paid'])->delete();

            $type = 'paid';
            $this->InsertVendorLog($purchase->vendor_id, $curr->id, $type, $purchase->total, $purchase->description, $curr->amount, 'purchase', $purchase->id, $currentDate);

            // $this->InsertVendorLog($purchase->vendor_id, $curr->id, 'received', $purchase->paid, $purchase->description, $curr->amount, 'purchase', $purchase->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('purchase.index')->with('success', 'Purchase stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('purchase.index')->with('error', 'Purchase Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating purchase: ' . $e->getMessage());
        }
    }
}
