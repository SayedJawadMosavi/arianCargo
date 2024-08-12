<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMainTransferRequest;
use App\Http\Requests\UpdateMainTransferRequest;
use App\Http\Traits\StockProductTrait;
use App\Models\MainTransfer;
use App\Models\MainTransferDetail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockSubProduct;
use App\Models\SubProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainTransferController extends Controller
{
    use StockProductTrait;
    protected $settings;
    public function __construct(Request $request)
    {
        $this->middleware('permission:main_transfer.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:main_transfer.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:main_transfer.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:main_transfer.delete', ['only' => ['destroy']]);
        $this->middleware('permission:main_transfer.restore', ['only' => ['restore']]);
        $this->middleware('permission:main_transfer.forceDelete', ['only' => ['forceDelete']]);
        $this->settings = $request->get('settings');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $main_transfers = MainTransfer::branch()->get();
        return view('main_transfer.index', compact('main_transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function singleTransfer()
    {
        $stocks = Stock::branch()->get();
        $products = SubProduct::branch()
        ->selectRaw('product_id, sum(available) as total_quantity')
        ->groupBy('product_id')
        ->havingRaw('total_quantity > 0')
        ->get();

        return view('main_transfer.create-single-transfer', compact('stocks', 'products'));
    }
    public function create()
    {
        $stocks = Stock::branch()->get();
        $products = SubProduct::branch()
        ->selectRaw('product_id, sum(available) as total_quantity')
        ->groupBy('product_id')
        ->havingRaw('total_quantity > 0')
        ->get();
        return view('main_transfer.create', compact('stocks', 'products'));
    }



    function allocateQuantity($productId, $quantity){
        $remainingQuantity = $quantity;

        // Retrieve sub-products for the given product ID, ordered by creation date (FIFO)
        $subProducts = SubProduct::where('product_id', $productId)->where('quantity', '>', 0)
            ->orderBy('created_at')
            ->get();

        // Loop through sub-products and allocate quantity based on FIFO
        foreach ($subProducts as $subProduct) {
            if ($remainingQuantity <= 0) {
                break; // All quantity allocated
            }

            // Calculate how much quantity can be allocated from this sub-product
            $quantityToAllocate = min($remainingQuantity, $subProduct->quantity);

            // Update the sub-product quantity
            $subProduct->quantity -= $quantityToAllocate;
            $subProduct->available -= $quantityToAllocate;
            $subProduct->save();

            // Decrease remaining quantity to allocate
            $remainingQuantity -= $quantityToAllocate;

            // Return the sub-product id along with the allocated quantity
            if ($quantityToAllocate > 0) {
                return ['quantity' => $quantityToAllocate, 'sub_product_id' => $subProduct->id];
            }
        }

        return ['quantity' => $quantity - $remainingQuantity, 'sub_product_id' => null]; // Return the actual allocated quantity
}

    public function store(StoreMainTransferRequest $request)
    {
        DB::beginTransaction();
        try {


            $transfer = MainTransfer::create([
                'stock_id' => $request->stock_id,
                // 'quantity' => $request->quantity, // Store allocated quantity
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            if($transfer){

                $products = $request->product;
                $quantity = $request->quantity;
                foreach ($products as $index => $item) {

                    $product =  Product::find($products[$index]);
                    if($product->subProducts->sum('available') < $quantity[$index]){
                        throw new \Exception(': مقدار کافی موجود نیست.'. $product->name);
                    }

                    $stock_product = $this->GetStockProduct($request->stock_id, $product->id);
                    // dd($stock_product);

                    $remainingQuantity = $quantity[$index];
                    // Retrieve sub-products for the given product ID, ordered by creation date (FIFO)
                    $subProducts = SubProduct::where('product_id', $product->id)
                        ->where('available', '>', 0)
                        ->orderBy('created_at')
                        ->get();

                    $main_transfer_detail = MainTransferDetail::create([
                        'main_transfer_id' => $transfer->id,
                        'product_id' => $product->id,
                        // 'sub_product_id' => $stock_product->id,
                        'stock_product_id' => $stock_product->id,
                        // 'stock_sub_product_id' => $product[$index],
                        'quantity' => $quantity[$index],
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

                        // Store allocated quantity in StockSubProduct
                        StockSubProduct::create([
                            'stock_product_id' => $stock_product->id,
                            'sub_product_id' => $subProduct->id,
                            'quantity' => $quantityToAllocate,
                            'available' => $quantityToAllocate,
                            'cost' => $subProduct->cost,
                            'expense' => $subProduct->expense,
                            'rent' => $subProduct->rent,
                            'other' => 0,
                            'income_price' => $subProduct->income_price,
                            'sell_price' => $subProduct->sell_price,
                            'branch_id' => auth()->user()->branch_id,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }
                DB::commit();
                return redirect()->route('main_transfer.index')->with('success', 'Main transfer stored successfully');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating main transfer: ' . $e->getMessage());
        }
    }



    public function storeSingleItemTransfer(StoreMainTransferRequest $request) // NOT USED ANYMORE
    {
        DB::beginTransaction();
        try {

            $product = Product::find($request->product_id);
            if($product->subProducts->sum('available') < $request->quantity){
                throw new \Exception('مقدار موجود کمتر از مقدار انتقال است.');
            }

            $stock_product = $this->GetStockProduct($request->stock_id, $request->product_id);
            $transfer = MainTransfer::create([
                'product_id' => $request->product_id,
                'stock_id' => $request->stock_id,
                'stock_product_id' => $stock_product->id,
                'quantity' => $request->quantity, // Store allocated quantity
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            if($transfer){

                // $product = $request->product;
                // $quantity = $request->quantity;


                $remainingQuantity = $request->quantity;

                // Retrieve sub-products for the given product ID, ordered by creation date (FIFO)
                $subProducts = SubProduct::where('product_id', $request->product_id)
                    ->where('available', '>', 0)
                    ->orderBy('created_at')
                    ->get();

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

                    // Store allocated quantity in StockSubProduct
                    StockSubProduct::create([
                        'stock_product_id' => $stock_product->id,
                        'sub_product_id' => $subProduct->id,
                        'quantity' => $quantityToAllocate,
                        'available' => $quantityToAllocate,
                        'cost' => $subProduct->cost,
                        'expense' => $subProduct->expense,
                        'rent' => $subProduct->rent,
                        'other' => 0,
                        'income_price' => $subProduct->income_price,
                        'sell_price' => $subProduct->sell_price,
                        'branch_id' => auth()->user()->branch_id,
                        'user_id' => auth()->user()->id,
                    ]);
                }

                DB::commit();
                return redirect()->route('main_transfer.index')->with('success', 'Main transfer stored successfully');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating main transfer: ' . $e->getMessage());
        }
    }

    public function getMainTransferDetail(MainTransfer $main_transfer, $id)
    {
        $main_transfer = MainTransfer::find($id);
        $details = MainTransferDetail::where('main_transfer_id', $id)->get();
        $products = Product::branch()->get();

        return view('main_transfer.detail', compact('main_transfer', 'details', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMainTransferRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreMainTransferRequest $request)
    // {
    //     $flag = false;
    //     dd($request->all());
    //     DB::beginTransaction();
    //     try {
    //         $stock_product = $this->GetStockProduct($request->stock_id, $request->product_id);
    //         $transfer = MainTransfer::create([
    //             'product_id' => $request->product_id,
    //             'stock_id' => $request->stock_id,
    //             'stock_product_id' => $stock_product->id,
    //             'quantity' => $request->quantity,
    //             'description' => $request->description,
    //             'miladi_date' => $request->miladi_date,
    //             'shamsi_date' => $request->shamsi_date,
    //             'branch_id' => auth()->user()->branch_id,
    //             'user_id' => auth()->user()->id,
    //         ]);

    //         $product = Product::find($request->product_id);
    //         if($transfer){
    //             $product->decrement('quantity', $request->quantity);
    //             $flag = $stock_product->increment('quantity', $request->quantity);
    //         }
    //         if($flag){
    //             DB::commit();
    //             return redirect()->route('main_transfer.index')->with('success', 'main transfer stored successfully');
    //         }else{
    //             DB::rollBack();
    //             return redirect()->route('main_transfer.create')->with('error', 'main transfer Failed');
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error creating sell: ' . $e->getMessage());
    //     }
    // }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainTransfer  $mainTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(MainTransfer $mainTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainTransfer  $mainTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(MainTransfer $mainTransfer)
    {
        $stocks = Stock::branch()->get();
        $products = SubProduct::branch()
        ->selectRaw('product_id, sum(available) as total_quantity')
        ->groupBy('product_id')
        ->havingRaw('total_quantity > 0')
        ->get();
        $main_transfer = $mainTransfer;
        return view('main_transfer.create', compact('stocks', 'products', 'main_transfer'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMainTransferRequest  $request
     * @param  \App\Models\MainTransfer  $mainTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMainTransferRequest $request, MainTransfer $mainTransfer)
    {
        $flag = false;
        // dd($request->all());
        DB::beginTransaction();

        try {
            $old_product = Product::find($mainTransfer->product_id);
            $old_product->increment('quantity', $mainTransfer->quantity);

            $old_stock_product = StockProduct::find($mainTransfer->stock_product_id);
            $old_stock_product->decrement('quantity', $mainTransfer->quantity);

            // ------------------ NEW TRANSFER ------------------
            $stock_product = $this->GetStockProduct($request->stock_id, $request->product_id);
            $transfer = $mainTransfer->update([
                'product_id' => $request->product_id,
                'stock_id' => $request->stock_id,
                'stock_product_id' => $stock_product->id,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,
            ]);

            $product = Product::find($request->product_id);
            if($transfer){
                $product->decrement('quantity', $request->quantity);
                $flag = $stock_product->increment('quantity', $request->quantity);
            }
            if($flag){
                DB::commit();
                return redirect()->route('main_transfer.index')->with('success', 'main transfer edited successfully');
            }else{
                DB::rollBack();
                return redirect()->route('main_transfer.create')->with('error', 'main transfer edit Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating main Transfer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainTransfer  $mainTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainTransfer $mainTransfer)
    {
        //
    }
}
