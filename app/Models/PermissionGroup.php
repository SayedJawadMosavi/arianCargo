<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function permissions()
    {
       return $this->belongsToMany('Spatie\Permission\Models\Permission',
       'permission_permission_groups','permission_group_id','permission_id');
    }
}
