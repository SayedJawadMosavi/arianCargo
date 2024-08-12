<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    protected $guarded = [];

    public function sell(){
        return $this->belongsTo(Sell::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function stock_product(){
        return $this->belongsTo(StockProduct::class);
    }
    public function sell_sub_detail(){
        return $this->hasMany(SellSubDetail::class);
    }


}
