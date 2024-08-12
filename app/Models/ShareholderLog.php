<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareholderLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function shareholder(){
        return $this->belongsTo(ShareHolder::class);
    }

    public function shareholder_currency(){
        return $this->belongsTo(ShareholderCurrency::class);
    }
}
