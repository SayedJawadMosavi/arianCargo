<?php
namespace App\Http\Traits;

use App\Models\StockProduct;

trait StockProductTrait
{
    private function GetStockProduct($stock, $product)
    {
        $stock_product = StockProduct::where('stock_id', $stock)->where('product_id', $product)->first();
        if(is_null($stock_product)){
            $stock_product = StockProduct::create([
                'stock_id' => $stock,
                'product_id' => $product,
                // 'quantity' => 0,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
        }
        return $stock_product;
        // return ['success' => true, 'fileName' => $stock_product];
    }

}
