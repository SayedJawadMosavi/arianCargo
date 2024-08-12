<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stock_transfer(){
        return $this->belongsTo(StockTransfer::class);
    }


    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

}
