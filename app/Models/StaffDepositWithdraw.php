<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDepositWithdraw extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
