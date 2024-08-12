<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTransferRequest;
use App\Http\Requests\UpdateStockTransferRequest;
use App\Http\Traits\StockProductTrait;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockSubProduct;
use App\Models\StockTransfer;
use App\Models\StockTransferDetail;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    use StockProductTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock_transfers = StockTransfer::with('stockTransfer')->branch()->get();
        return view('stock_transfer.index', compact('stock_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stocks = Stock::branch()->get();
        return view('stock_transfer.create', compact('stocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStockTransferRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockTransferRequest $request)
    {
        $flag = false; $transfer_detail = false;
        DB::beginTransaction();
        try {
            $sender_product = StockProduct::find($request->product_id);
            $receiver_product = $this->GetStockProduct($request->to_stock, $sender_product->product_id);
            // dd($request->all());
            // dd($receiver_product->id);
            $transfer = StockTransfer::create([
                'sender_stock_id' => $request->from_stock,
                'sender_product_id' => $sender_product->id,
                'receiver_stock_id' => $request->to_stock,
                'receiver_product_id' => $receiver_product->id,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
            if($transfer){
                // $flag = $sender_product->decrement('quantity', $request->quantity);
                // $flag = $receiver_product->increment('quantity', $request->quantity);

                $remainingQuantity = $request->quantity;

                // Retrieve sub-products for the given product ID, ordered by creation date (FIFO)
                $subProducts = StockSubProduct::where('stock_product_id', $sender_product->id)
                    ->where('available', '>', 0)
                    ->orderBy('created_at')
                    ->get();
                // dd($subProducts);
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

                    $receiver_sub_product = StockSubProduct::create([
                        'stock_product_id' => $receiver_product->id,
                        'sub_product_id' => $subProduct->sub_product_id,
                        'quantity' => $quantityToAllocate,
                        'available' => $quantityToAllocate,
                        'cost' => $subProduct->cost,
                        'expense' => $subProduct->expense,
                        'rent' => $subProduct->rent,
                        'other' => 0,
                        'income_price' => $subProduct->income_price,
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);

                    // Store allocated quantity in StockSubProduct
                    $transfer_detail = StockTransferDetail::create([
                        'stock_transfer_id' => $transfer->id,
                        'sender_stock_sub_product_id' => $subProduct->id,
                        'receiver_stock_sub_product_id' => $receiver_sub_product->id,
                        'quantity' => $quantityToAllocate,
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);
                    // dd($transfer_detail);
                }
                // end FIFO Transaction
            }
            if($transfer_detail){
                DB::commit();
                return redirect()->route('stock_transfer.index')->with('success', 'Stock Transfer stored successfully');
            }else{
                DB::rollBack();
                return redirect()->route('stock_transfer.create')->with('error', 'Stock Transfer Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating sell: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockTransfer  $stockTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(StockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTransfer  $stockTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(StockTransfer $stockTransfer)
    {
        $stocks = Stock::branch()->get();
        return view('stock_transfer.create', compact('stocks', 'stockTransfer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockTransferRequest  $request
     * @param  \App\Models\StockTransfer  $stockTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockTransferRequest $request, StockTransfer $stockTransfer)
    {
        dd('not allowed');
        $flag = false;
        DB::beginTransaction();
        try {
            $sender_product = StockProduct::find($request->product_id);
            $receiver_product = $this->GetStockProduct($request->to_stock, $sender_product->product_id);
            $transfer = $stockTransfer->update([
                'sender_stock_id' => $request->from_stock,
                'sender_product_id' => $sender_product->id,
                'receiver_stock_id' => $request->to_stock,
                'receiver_product_id' => $receiver_product->id,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
            if($transfer){
                $flag = $sender_product->decrement('quantity', $request->quantity);
                $flag = $receiver_product->increment('quantity', $request->quantity);
            }
            if($flag){
                DB::commit();
                return redirect()->route('stock_transfer.index')->with('success', 'Stock Transfer stored successfully');
            }else{
                DB::rollBack();
                return redirect()->route('stock_transfer.create')->with('error', 'Stock Transfer Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating sell: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTransfer  $stockTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockTransfer $stockTransfer)
    {
        //
    }

    public function getStock($id)
    { //used in Stock Transfer Create
        $html = '';
        $stocks = Stock::where('id' ,'!=',$id)->branch()->get();

        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($stocks as $obj) {
            $html .= '<option value="' . $obj->id . '">' .$obj->name .'</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function getStockProducts($id)
    { //used in Stock Transfer Create
        $html = '';
        // $products = StockProduct::where('stock_id', $id)->where('quantity', '>', 0)->get();

        $products = StockSubProduct::with('stockProduct')->
        whereHas('stockProduct', function ($query) use ($id) {
            $query->where('stock_id', $id);
        })
        ->selectRaw('stock_product_id, sum(available) as total_quantity')
        ->groupBy('stock_product_id')
        ->havingRaw('total_quantity > 0')
        ->get();


        // dd($products);
        $html .= '<option selected disabled value="">Choose ...</option>';
        foreach ($products as $obj) {
            $html .= '<option value="' . $obj->stock_product_id . '" data-quantity="' . $obj->total_quantity . '">' .$obj->stockProduct->product->name . '('.$obj->total_quantity.')'.'</option>';
        }
        return response()->json(['html' => $html]);
    }
}
