<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceivedRequest;
use App\Models\Account;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\Receive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Models\SubProduct;

include "PersianCalendar.php";

class ReceiveController extends Controller
{
    use AccountLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceivedRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $balance = $request->total - $request->paid;

            if(!isset($request->product)){
                throw new \Exception('Product list can not be empty');
            }

            $account = Account::find($request->account_id);
            if ($account->amount < $request->total) {
                throw new \Exception('Insufficient account balance');
            }
            $product = $request->product;

            $expense = $request->expense;
            $rent = $request->rent;
            $quantity = $request->quantity;
            $sell = $request->sell;
            $purchase_detail_id = $request->purchase_detail_id;

            // $description = ' Receiving : '.$purchase->id.' - '.$request->description;

            $receive=   Receive::orderBy('id','desc')->first();
            if ($receive==null) {
              $no=1;
            }else{
                $no=$receive->no+1;
            }
            foreach ($request->product as $index => $item) {
                if($quantity[$index] > 0){
                    $purchase_detail = PurchaseDetail::find($purchase_detail_id[$index]);

                    $prod = Product::find($purchase_detail->product_id);
                    // dd($purchase_detail);
                    if ($purchase_detail->quantity-$purchase_detail->received < $quantity[$index]) {
                        throw new \Exception('QTY Can not be greater than Purchase quantity');
                    }

                    $cost = $purchase_detail->cost + $expense[$index] + $rent[$index];
                    $prod->update([
                        'sell_price' => $sell[$index],
                        'income_price' => $cost
                    ]);

                    $receive=  Receive::create([
                        'purchase_detail_id' => $purchase_detail_id[$index],
                        'purchase_id' => $purchase_detail->purchase_id,
                        'product_id' => $purchase_detail->product_id,
                        'quantity' => $quantity[$index],
                        'sell_price' => $sell[$index],
                        'no' => $no,
                        'expense' => $expense[$index],
                        'rent' => $rent[$index],
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);

                    // $prod->update([
                    //     'currency_id' => $account->currency_id,
                    //     'cost' => $cost,
                    //     'sell' =>  $sell[$index],
                    // ]);
                    // $prod->increment('quantity', $quantity[$index]);
                    $purchase_detail->increment('received', $quantity[$index]);
                    SubProduct::create([
                        'product_id' => $prod->id,
                        'receive_id' => $receive->id,
                        'purchase_id' => $purchase_detail->purchase_id,
                        'quantity' => $quantity[$index],
                        'available' => $quantity[$index],
                        'cost' => $purchase_detail->cost,
                        'expense' => $expense[$index],
                        'rent' => $rent[$index],
                        'other' => 0,
                        'income_price' => $cost,
                        'sell_price' => $sell[$index],
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }

            $type = 'withdraw';
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            if ($request->total>0 ) {

                if ($account->amount !== null) {
                    // If amount is not null, add $request->paid to the existing amount
                    $account->decrement('amount', (float) $request->total);
                } else {
                    // If amount is null, set it to the value of $request->paid
                    $total = 0 - $request->total;
                    $account->update(['amount' => (float) $total]);
                }

            }
            $flag = $this->InsertAccountLog($request->account_id, $type, $request->total, $request->description, $account->amount, 'received', $receive->no, $currentDate);
            // dd($flag);



            if($receive){
                DB::commit();
                return redirect()->back()->with('success', 'Recieve stored successfully');
            }else{
                DB::rollBack();
                return redirect()->back()->with('error', 'Recieve Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating recieve: ' . $e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function show(Receive $receive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function edit(Receive $receive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receive $receive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receive  $receive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receive $receive)
    {
        dd($receive);
    }
}
