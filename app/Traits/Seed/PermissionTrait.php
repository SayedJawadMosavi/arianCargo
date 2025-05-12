<?php

namespace App\Traits\Seed;

use App\Models\PermissionGroup;
use Spatie\Permission\Models\Permission;

use DB;

trait PermissionTrait
{
    /**
     * seedAndCheckPermission function
     * seed permissions and check existing permission
     * @return void
     */
    public function seedAndCheckPermission()
    {

        // start user management

        //  permission group table
        $g = (new PermissionGroup())->where('name', 'user')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'user',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [

            [
                'name' => 'users.view',
                'display_name'    => "View Users"
            ],
            [
                'name' => 'users.create',
                'display_name'    => "Create Users"
            ],
            [
                'name' => 'users.edit',
                'display_name'    => "Edit Users"
            ],
            [
                'name' => 'users.delete',
                'display_name'    => "Delete Users"
            ]
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end
        // start of branch
        $g = (new PermissionGroup())->where('name', 'branch')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'branch',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [

            [
                'name' => 'branch.view',
                'display_name'    => "View Branch"
            ],
            [
                'name' => 'branch.create',
                'display_name'    => "Create Branch"
            ],
            [
                'name' => 'branch.edit',
                'display_name'    => "Edit Branch"
            ],
            [
                'name' => 'branch.delete',
                'display_name'    => "Delete Branch"
            ]
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        // end of branch
        //  permission group table

        $g = (new PermissionGroup())->where('name', 'role')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'role',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'roles.view',
                'display_name'    => "View Role"
            ],
            [
                'name' => 'roles.create',
                'display_name'    => "Create Role"
            ],
            [
                'name' => 'roles.edit',
                'display_name'    => "Edit Role"
            ],
            [
                'name' => 'roles.delete',
                'display_name'    => "Delete Role"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end

        // end user management

        $g = (new PermissionGroup())->where('name', 'permissions')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'permissions',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'permissions.view',
                'display_name'    => "View Permissions"
            ],
            [
                'name' => 'permissions.create',
                'display_name'    => "Create Permissions"
            ],
            [
                'name' => 'permissions.edit',
                'display_name'    => "Edit Permissions"
            ],
            [
                'name' => 'permissions.delete',
                'display_name'    => "Delete Permissions"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //  permission group table
        $g = (new PermissionGroup())->where('name', 'category')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'category',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'category.view',
                'display_name'    => "View Category"
            ],
            [
                'name' => 'category.create',
                'display_name'    => "Create Category"
            ],
            [
                'name' => 'category.edit',
                'display_name'    => "Edit Category"
            ],
            [
                'name' => 'category.delete',
                'display_name'    => "Delete Category"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //  permission group table
        $g = (new PermissionGroup())->where('name', 'expense_category')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'expense_category',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'expense_category.view',
                'display_name'    => "View Category"
            ],
            [
                'name' => 'expense_category.create',
                'display_name'    => "Create Category"
            ],
            [
                'name' => 'expense_category.edit',
                'display_name'    => "Edit Category"
            ],
            [
                'name' => 'expense_category.delete',
                'display_name'    => "Delete Category"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //  permission group table
        $g = (new PermissionGroup())->where('name', 'expense')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'expense',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'expense.view',
                'display_name'    => "View Category"
            ],
            [
                'name' => 'expense.create',
                'display_name'    => "Create Category"
            ],
            [
                'name' => 'expense.edit',
                'display_name'    => "Edit Category"
            ],
            [
                'name' => 'expense.delete',
                'display_name'    => "Delete Category"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end
        //  permission group table



        // end post management
        //  permission group table

        $g = (new PermissionGroup())->where('name', 'currency')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'currency',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'currency.view',
                'display_name'    => "View Currency"
            ],
            [
                'name' => 'currency.create',
                'display_name'    => "Create Currency"
            ],
            [
                'name' => 'currency.edit',
                'display_name'    => "Edit Currency"
            ],
            [
                'name' => 'currency.delete',
                'display_name'    => "Delete Currency"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end

        // end page management
        //  permission group table

        $g = (new PermissionGroup())->where('name', 'client')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'client',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'client.view',
                'display_name'    => "View Client"
            ],
            [
                'name' => 'client.create',
                'display_name'    => "Create Client"
            ],
            [
                'name' => 'client.edit',
                'display_name'    => "Edit Client"
            ],
            [
                'name' => 'client.delete',
                'display_name'    => "Delete Client"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end
        $g = (new PermissionGroup())->where('name', 'client_transaction')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'client_transaction',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'client_transaction.view',
                'display_name'    => "View Client Transaction"
            ],
            [
                'name' => 'client_transaction.create',
                'display_name'    => "Create Client Transaction"
            ],
            [
                'name' => 'client_transaction.edit',
                'display_name'    => "Edit Client Transaction"
            ],
            [
                'name' => 'client_transaction.delete',
                'display_name'    => "Delete Client Transaction"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end

        //  permission group table

        $g = (new PermissionGroup())->where('name', 'sell')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'sell',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'sell.view',
                'display_name'    => "View Sell"
            ],
            [
                'name' => 'sell.create',
                'display_name'    => "Create Sell"
            ],
            [
                'name' => 'sell.edit',
                'display_name'    => "Edit Sell"
            ],
            [
                'name' => 'sell.delete',
                'display_name'    => "Delete Sell"
            ],
            [
                'name' => 'sell.details',
                'display_name'    => "Sell Details"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end


        $g = (new PermissionGroup())->where('name', 'expense')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'expense',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'expense.view',
                'display_name'    => "View Expense"
            ],
            [
                'name' => 'expense.create',
                'display_name'    => "Create Expense"
            ],
            [
                'name' => 'expense.edit',
                'display_name'    => "Edit Expense"
            ],
            [
                'name' => 'expense.delete',
                'display_name'    => "Delete Expense"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }

        $g = (new PermissionGroup())->where('name', 'unit')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'unit',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'unit.view',
                'display_name'    => "View Unit"
            ],
            [
                'name' => 'unit.create',
                'display_name'    => "Create Unit"
            ],
            [
                'name' => 'unit.edit',
                'display_name'    => "Edit Unit"
            ],
            [
                'name' => 'unit.delete',
                'display_name'    => "Delete Unit"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end


        $g = (new PermissionGroup())->where('name', 'setting')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'setting',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'setting.view',
                'display_name'    => "View Settings"
            ],
            [
                'name' => 'setting.create',
                'display_name'    => "Create Settings"
            ],
            [
                'name' => 'setting.edit',
                'display_name'    => "Edit Settings"
            ],
            [
                'name' => 'setting.delete',
                'display_name'    => "Delete Settings"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //end

        // end settings management
        //  permission group table


        // end Subscribe Email management
        $g = (new PermissionGroup())->where('name', 'account')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'account',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'account.view',
                'display_name'    => "View Account"
            ],

            [
                'name' => 'account.create',
                'display_name'    => "Create Account"
            ],
            [
                'name' => 'account.edit',
                'display_name'    => "Edit Account"
            ],
            [
                'name' => 'account.delete',
                'display_name'    => "Delete Account"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //  permission group table

        $g = (new PermissionGroup())->where('name', 'account_transaction')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'account_transaction',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'account_transaction.view',
                'display_name'    => "View Account Transaction"
            ],

            [
                'name' => 'account_transaction.create',
                'display_name'    => "Create Account Transaction"
            ],
            [
                'name' => 'account_transaction.edit',
                'display_name'    => "Edit Account Transaction"
            ],
            [
                'name' => 'account_transaction.delete',
                'display_name'    => "Delete Account Transaction"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        $g = (new PermissionGroup())->where('name', 'account_transfer')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'account_transfer',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'account_transfer.view',
                'display_name'    => "View Account Transfer"
            ],

            [
                'name' => 'account_transfer.create',
                'display_name'    => "Create Account Transfer"
            ],
            [
                'name' => 'account_transfer.edit',
                'display_name'    => "Edit Account Transfer"
            ],
            [
                'name' => 'account_transfer.delete',
                'display_name'    => "Delete Account Transfer"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //  permission group table


        $g = (new PermissionGroup())->where('name', 'staff_transaction')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'staff_transaction',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'staff_transaction.view',
                'display_name'    => "View Staff Transaction"
            ],
            [
                'name' => 'staff_transaction.create',
                'display_name'    => "Create Staff Transaction"
            ],
            [
                'name' => 'staff_transaction.edit',
                'display_name'    => "Edit Staff Transaction"
            ],
            [
                'name' => 'staff_transaction.delete',
                'display_name'    => "Delete Staff Transaction"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }

        // schedule
        $g = (new PermissionGroup())->where('name', 'staff')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'staff',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'staff.view',
                'display_name'    => "View Staff"
            ],
            [
                'name' => 'staff.create',
                'display_name'    => "Create Staff"
            ],
            [
                'name' => 'staff.edit',
                'display_name'    => "Edit Staff"
            ],
            [
                'name' => 'staff.delete',
                'display_name'    => "Delete Staff"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        $g = (new PermissionGroup())->where('name', 'staff_salary')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'staff_salary',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'staff_salary.view',
                'display_name'    => "View Staff Salary"
            ],
            [
                'name' => 'staff_salary.create',
                'display_name'    => "Create Staff Salary"
            ],
            [
                'name' => 'staff_salary.edit',
                'display_name'    => "Edit Staff Salary"
            ],
            [
                'name' => 'staff_salary.delete',
                'display_name'    => "Delete Staff Salary"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }
        //    ---------------------------------------


        $g = (new PermissionGroup())->where('name', 'document')->first();
        if (!$g) {
            $g = PermissionGroup::create([
                'name' => 'document',
                'category' => 'admin',
            ]);
        }
        //  permission  table
        $permissions = [
            [
                'name' => 'document.view',
                'display_name'    => "View document"
            ],
            [
                'name' => 'document.create',
                'display_name'    => "Create document"
            ],
            [
                'name' => 'document.edit',
                'display_name'    => "Edit document"
            ],
            [
                'name' => 'document.delete',
                'display_name'    => "Delete document"
            ],
            [
                'name' => 'document.restore',
                'display_name'    => "Restore document"
            ],
            [
                'name' => 'document.forceDelete',
                'display_name'    => "Force Delete document"
            ],
        ];
        foreach ($permissions as $key => $value) {
            $p = (new Permission())->where('name', $value)->first();
            if (!$p) {
                $p = Permission::create($value);
                $g->permissions()->sync($p->id, false);
            }
        }

         // ---------------------------------------
         $g = (new PermissionGroup())->where('name', 'report')->first();
         if (!$g) {
             $g = PermissionGroup::create([
                 'name' => 'report',
                 'category' => 'admin',
             ]);
         }
         //  permission  table
         $permissions = [
             [
                 'name' => 'report.expense',
                 'display_name'    => "Expense Report"
             ],

             [
                 'name' => 'report.all_vailable_report',
                 'display_name'    => "All Available Report"
             ],
             [
                 'name' => 'report.sell',
                 'display_name'    => "Sell Report"
             ],

             [
                 'name' => 'report.account_log',
                 'display_name'    => "Account Log Report"
             ],



         ];
         foreach ($permissions as $key => $value) {
             $p = (new Permission())->where('name', $value)->first();
             if (!$p) {
                 $p = Permission::create($value);
                 $g->permissions()->sync($p->id, false);
             }
         }
    }

}
