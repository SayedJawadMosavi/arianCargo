<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
