<?php

namespace App\Traits;

use App\Models\Role;
use Spatie\Permission\Models\Permission;



trait PermissionsTrait
{

    public function givePermissionsTo(...$permissions): PermissionsTrait
    {

        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo(...$permissions): PermissionsTrait
    {

        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;

    }

    public function refreshPermissions(...$permissions)
    {

        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission): bool
    {

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission): bool
    {

        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole(...$roles): bool
    {

        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles()
    {

        return $this->belongsToMany(Role::class, 'user_role');

    }

    public function permissions()
    {

        return $this->belongsToMany(Permission::class, 'permission_user');

    }

    protected function hasPermission($permission): bool
    {

        return (bool)$this->permissions->where('name', $permission->name)->count();
    }

    protected function getAllPermissions(array $permissions)
    {

        return Permission::whereIn('name', $permissions)->get();

    }


}
