<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SellDetail;
use App\Models\SellReturn;
use App\Models\Setting;
use App\Models\StockProduct;
use App\Models\SubProduct;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::branch()->get();
        $trashed = Product::branch()->onlyTrashed()->get();
        return view('product.index', compact('products', 'trashed'));
    }

    public function minStock()
    {
        // $products = Product::branch()->whereColumn('quantity', '<=', 'min_stock')->get();

        $products = Product::branch()
        ->where(function ($query) {
            $query->where('min_stock', '>', function ($subquery) {
                $subquery->selectRaw('SUM(available)')
                    ->from('sub_products')
                    ->whereColumn('sub_products.product_id', 'products.id');
            });
        })
        ->get();

        $trashed = Product::branch()->onlyTrashed()->get();
        return view('product.min', compact('products', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::active()->get();
        $currencies = Currency::active()->get();
        $units = Unit::active()->get();
        return view('product.create', compact('currencies', 'categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            if(!$base_currency || $base_currency==''){
                throw new \Exception('Please go to Settings and choose your base currency');
            }
            $branch_base = $base_currency->currency_id; //

            $product = Product::create([
                'category_id' => $request->category_id,
                'currency_id' => $branch_base,
                'unit_id' => $request->unit_id,
                'name' => $request->name,
                'code' => $request->code,
                'size' => $request->size,
                'model' => $request->model,
                'height' => $request->height,
                'width' => $request->width,
                'length' => $request->length,
                'weight' => $request->weight,
                // 'quantity' => $request->quantity,
                // 'initial_quantity' => $request->quantity,
                // 'cost' => $request->cost,
                // 'sell' => $request->sell,
                'min_stock' => $request->min_stock,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'active' => 1
            ]);

            $products = Product::branch()->get();

            $trashed = Product::branch()->withTrashed()->get();
            // dd($products);

            if ($product) {
                DB::commit();
                return redirect()->route('product.index', compact('products', 'trashed'))->with('success', 'Product created successfully');
            } else {
                DB::rollBack();
                return redirect()->route('product.create')->with('error', 'Product registration Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        // Retrieve the product_stock_id associated with the product_id
        $product_stock_ids = StockProduct::where('product_id', $product->id)->pluck('id');


            // Retrieve SellItems based on the product_id
            $sells = SellDetail::with('product', 'sell')->where('product_id', $product->id)->get();

            // Optionally, you can load other related data
            $purchases = PurchaseDetail::where('product_id',$product->id)->get();

            $returns = SellReturn::whereIn('stock_product_id', $product_stock_ids)->get();
            // Return the response
           return view('product.show',compact('sells','purchases','returns'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $units = Unit::active()->get();
        // dd($product);
        $categories = Category::active()->get();
        $currencies = Currency::active()->get();
        return view('product.create', compact('product','currencies', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $reques
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // dd($product);
        $active = isset($request->active) ? 1 : 0;
        $product->update([
            'category_id' => $request->category_id,
            // 'currency_id' => $request->currency_id,
            'name' => $request->name,
            'code' => $request->code,
            'size' => $request->size,
            // 'quantity' => $request->quantity,
            // 'cost' => $request->cost,
            // 'sell' => $request->sell,
            'model' => $request->model,
            'height' => $request->height,
            'width' => $request->width,
            'length' => $request->length,
            'weight' => $request->weight,
            'min_stock' => $request->min_stock,
            'branch_id' => auth()->user()->branch_id,
            'active' => $active,
            'updated_by' => auth()->user()->id,
        ]);

        $products = Product::branch()->get();
        $trashed = Product::branch()->withTrashed()->get();
        // dd($products);
        return redirect()->route('product.index', compact('products', 'trashed'))->with('success', 'Product updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */


    public function restore(string $id)
    {
        try {
            $ids = explode(",", $id);
            Product::whereIn('id', $ids)->withTrashed()->restore();
            return response()->json(true, 203);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function forceDelete(string $id)
    {
        try {
            DB::beginTransaction();
            $ids = explode(",", $id);
            Product::whereIn('id', $ids)->withTrashed()->forceDelete();
            DB::commit();
            return response()->json(true, 203);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            DB::beginTransaction();
            $ids  = explode(",", $id);
            $result = Product::whereIn("id", $ids)->delete();
            DB::commit();
            return response()->json($result, 206);
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function changeStatus($id,$value)
    {
        try {
            if ($id==1) {
                $product=Product::where('id',$value)->update(['status'  =>0]);
            }else if ($id==0) {
                $product=Product::where('id',$value)->update(['status'  =>1]);
            }
            return response()->json($product, 202);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function productLog(Request $request, $id){
        dd(Product::find($id));

        // $sell = SellDetail::where
    }


    public function productStock($id)
    {
        // dd($id);
        $products = SubProduct::with('product')->where('product_id', $id)->get();
        return view('product.sub', compact('products'));

    }

}
