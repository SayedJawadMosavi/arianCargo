<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function vendor_currency(){
        return $this->belongsTo(VendorCurrency::class);
    }

}
