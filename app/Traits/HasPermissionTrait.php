<?php

namespace App\Traits;

use App\Models\User;
use Auth;

trait HasPermissionTrait
{

    // check if user has permission
    public function hasPermission($permissions = array())
    {
        $user = auth()->user();
        return (new User())->userPermissionsCheck($user->id, $permissions, false);
    }

}

?>
