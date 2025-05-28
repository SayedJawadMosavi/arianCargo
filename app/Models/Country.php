<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'description', 'rate', 'active', 'user_id', 'branch_id'];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id'); // 'country' is the foreign key in clients table
    }
}
