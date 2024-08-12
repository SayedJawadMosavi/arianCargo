<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareholderTransaction extends Model
{

    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function share_holder(){
        return $this->belongsTo(ShareHolder::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function shareholder_currency(){
        return $this->belongsTo(ShareholderCurrency::class);
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
