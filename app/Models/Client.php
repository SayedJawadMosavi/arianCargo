<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable= ['name', 'mobile', 'address','country_id','zipcode', 'nid', 'photo', 'user_id', 'branch_id', 'client_id', 'updated_by', 'active', 'deleted_by'];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
   public function country()
{
    return $this->belongsTo(Country::class, 'country_id'); // 'country' is the foreign key in clients table
}

    public function currency(){
        return $this->hasOne(ClientCurrency::class);
    }

}
