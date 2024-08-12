<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function stock(){
        return $this->belongsTo(Stock::class);
    }

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }


    public function subProducts(){
        return $this->hasMany(StockSubProduct::class);
    }

    public function getGrandTotalAttribute(): float  //attribute called in stock_product as grand_total
    {
        return $this->subProducts()
            ->where('available', '>', 0)
            ->get()
            ->sum(function ($subProduct) {
                return $subProduct->available * $subProduct->income_price;
            });
    }
}
