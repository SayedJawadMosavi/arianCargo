<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SellDetail;
use App\Models\SellReturn;
use App\Models\Stock;
use App\Models\StockProduct;
use App\Models\StockSubProduct;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::branch()->get();
        $trashed = Stock::branch()->onlyTrashed()->get();

        return view('stock.index', compact('stocks', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStockRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockRequest $request)
    {
        Stock::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Product $stock)
    {

        $product_stock_ids = StockProduct::where('product_id', $stock->id)->pluck('id');

        // Retrieve SellItems based on the product_id
        $sells = SellDetail::where('product_id', $stock->id)->get();

        // Optionally, you can load other related data
        $purchases = PurchaseDetail::where('product_id',$stock->id)->get();

        $returns = SellReturn::whereIn('stock_product_id', $product_stock_ids)->get();
        // Return the response
       return view('product.show',compact('sells','purchases','returns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        return view('stock.create', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockRequest  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'updated_by' => auth()->user()->id,
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }

    public function products($id){
        $products = StockProduct::with('product', 'subProducts')->where('stock_id', $id)->get();
        $stock = Stock::find($id);
        return view('stock.products', compact('products', 'stock'));
    }
    public function productsList(){
        $branchId = auth()->user()->branch_id;

        $stockProducts = StockProduct::whereHas('stock', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->get();

        return view('stock.product-list')->with('products', $stockProducts);
    }
    public function MainsList()
    {
          $logs=  Product::branch()->get();
        return view('stock.main_stock', compact('logs'));
    }


    public function subStockProducts($id){
        $products = StockSubProduct::with('stockProduct')->where('stock_product_id', $id)->get();
        return view('stock.substock', compact('products'));
    }


}
