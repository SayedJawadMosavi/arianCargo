<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'branch_id', 'currency_id', 'amount', 'description', 'active', 'default', 'user_id'];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }


    public static function rich(){
        return Account::where(['active' => 1, 'branch_id' => auth()->user()->branch_id, 'amount', '>', 0])->get();
    }

}
