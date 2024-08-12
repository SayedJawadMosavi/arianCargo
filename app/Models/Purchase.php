<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function detail(){
        return $this->hasMany(PurchaseDetail::class);
    }
    public function receive(){
        return $this->hasMany(Receive::class);
    }

}
