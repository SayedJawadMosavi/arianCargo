<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\Seed\PermissionTrait;

class PermissionTableSeeder extends Seeder

{
    use PermissionTrait;
    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $this->seedAndCheckPermission();
        // $permissions = [

        //    'roles.create'                           => 'Create Role',
        //    'roles.view'                           => 'View Role',
        //    'roles.edit'                             => 'Edit Role',
        //    'roles.delete'                           => 'Delete Role',

        //     'users.view'                            => 'View User',
        //    'users.create'                           => 'Create User',
        //    'users.edit'                             => 'Edit User',
        //    'users.delete'                           => 'Delete User',

        //     'permissions.view'                            => 'View Permission',
        //    'permissions.create'                           => 'Create Permission',
        //    'permissions.edit'                             => 'Edit Permission',
        //    'permissions.delete'                           => 'Delete Permission',

        //    'post.view'                             => 'View Post',
        //    'post.create'                           => 'Create Post',
        //    'post.edit'                             => 'Edit Post',
        //    'post.delete'                           => 'Delete Post',

        //    'setting.view'                             => 'View Setting',
        //    'setting.create'                           => 'Create Setting',
        //    'setting.edit'                             => 'Edit Setting',
        //    'setting.delete'                           => 'Delete Setting',

        //    'category.view'                             => 'View Category',
        //    'category.create'                           => 'Create Category',
        //    'category.edit'                             => 'Edit Category',
        //    'category.delete'                           => 'Delete Category',

        //    'product.view'                             => 'View Product',
        //    'product.create'                           => 'Create Product',
        //    'product.edit'                             => 'Edit Product',
        //    'product.delete'                           => 'Delete Product',

        // ];

        // foreach ($permissions as $key => $value) {
        //      Permission::create([
        //         'name' => $key,
        //         'guard_name' => 'web',
        //         'display_name' => $value

        //     ]);
        // }


    }

}
