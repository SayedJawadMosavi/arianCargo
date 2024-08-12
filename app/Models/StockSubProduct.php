<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSubProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subProduct(){
        return $this->belongsTo(SubProduct::class);
    }
    public function stockProduct(){
        return $this->belongsTo(StockProduct::class);
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
