<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    protected $guarded = [];

    public function cargo(){
        return $this->belongsTo(Cargo::class);
    }



}
