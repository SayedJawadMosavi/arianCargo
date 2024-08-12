<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable= ['company', 'contact_person', 'mobile', 'address', 'nid', 'photo', 'license', 'user_id', 'branch_id', 'client_id', 'updated_by', 'deleted_by'];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

    public function currency(){
        return $this->hasOne(VendorCurrency::class);
    }
    public function vendor_log(){
        return $this->hasMany(VendorLog::class);
    }
}
