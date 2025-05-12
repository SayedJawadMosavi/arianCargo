<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cargo extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function receiver(){
        return $this->belongsTo(Client::class, 'receiver_id');
    }
    public function detail(){
        return $this->hasMany(CargoDetail::class);
    }
    public function payments()
    {
        return $this->hasMany(CargoPayment::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
