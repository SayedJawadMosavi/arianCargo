<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
