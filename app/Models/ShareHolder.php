<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareHolder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable= ['name', 'mobile', 'address', 'nid', 'percentage', 'description','user_id', 'branch_id', 'updated_by', 'active', 'deleted_by'];

    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }
    public function currency(){
        return $this->hasMany(ShareholderCurrency::class,'share_holders_id');
    }
}
