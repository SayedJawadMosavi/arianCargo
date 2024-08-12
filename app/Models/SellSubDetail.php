<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellSubDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function sell_detail(){
        return $this->belongsTo(SellDetail::class);
    }
}
