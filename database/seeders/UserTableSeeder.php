<?php



namespace Database\Seeders;



use Illuminate\Database\Seeder;
use App\Models\User;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



class UserTableSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'branch_id' => 1,
            'active' => 1,
            'password' => '$2y$10$9nvTOpIXKNVQHYlulV/JOeKcRmbmCqWF.5MzZjDdP4enlqJhex1LC',
        ]);

        $role = Role::create(['name' => 'admin','guard_name'  =>'web']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->name]);

    }

}
