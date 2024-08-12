<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function stock(){
        return $this->belongsTo(Stock::class);
    }

    public function details(){
        return $this->hasMany(MainTransferDetail::class);
    }

}
