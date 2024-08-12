<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'active','user_id', 'branch_id', 'default'];

    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
