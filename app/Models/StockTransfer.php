<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function sender(){
        return $this->belongsTo(Stock::class, 'sender_stock_id');
    }

    public function receiver(){
        return $this->belongsTo(Stock::class, 'receiver_stock_id');
    }
    public function sender_product(){
        return $this->belongsTo(StockProduct::class, 'sender_product_id');
    }

    public function stockTransfer(){
        return $this->hasMany(StockTransferDetail::class);
    }

}
