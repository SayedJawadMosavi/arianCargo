<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'short_name', 'active', 'user_id', 'branch_id'];

    public function scopeActive($query){
        return $query->where('active', 1);
    }

}
