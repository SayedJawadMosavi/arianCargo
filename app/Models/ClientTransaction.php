<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTransaction extends Model
{

    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function client_currency(){
        return $this->belongsTo(ClientCurrency::class);
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
