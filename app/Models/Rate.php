<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function treasury(){
        return $this->belongsTo(Currency::class, 'from_treasury');
    }
    public function toTreasury(){
        return $this->belongsTo(Currency::class, 'to_treasury');
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
