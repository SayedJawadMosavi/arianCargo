<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function client_currency(){
        return $this->belongsTo(ClientCurrency::class);
    }
}
