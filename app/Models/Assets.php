<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use HasFactory ,SoftDeletes;
    protected $guarded = [];

    public function assets_category(){
        return $this->belongsTo(AssetsCategory::class,'category_id');
    }
    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

}
