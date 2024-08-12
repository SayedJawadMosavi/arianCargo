<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(DocumentCategory::class,'document_category_id');
    }
    public function scopeBranch($query){
        return $query->where('branch_id', auth()->user()->branch_id);
    }

}
