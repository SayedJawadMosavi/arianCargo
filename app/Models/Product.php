<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
    public function scopeActive($query){
        return $query->where('active', 1);
    }
    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function stockProducts()
    {
        return $this->hasMany(StockProduct::class, 'product_id', 'id');
    }
    public function purchase()
    {
        return $this->hasMany(PurchaseDetail::class, 'product_id');
    }
    public function sell()
    {
        return $this->hasMany(SellDetail::class, 'product_stock_id');
    }

    public function subProducts(){
        return $this->hasMany(SubProduct::class);
    }

    public function getGrandTotalAttribute(): float         //attribute called in stock_product as grand_total
    {
        return $this->subProducts()
            ->where('available', '>', 0)
            ->get()
            ->sum(function ($subProduct) {
                return $subProduct->available * $subProduct->income_price;
            });
    }

}
