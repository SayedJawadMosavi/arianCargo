<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainTransferDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function main_transfer(){
        return $this->belongsTo(MainTransfer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
