<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorCurrency extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
