<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransfer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function from_account(){
        return $this->belongsTo(Account::class,'sender_account_id');
    }
    public function to_account(){
        return $this->belongsTo(Account::class,'receiver_account_id');
    }
    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
