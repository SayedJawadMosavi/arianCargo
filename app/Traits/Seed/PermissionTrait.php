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
    $permissionGroups = [
        'user' => [
            ['name' => 'users.view', 'display_name' => 'View Users'],
            ['name' => 'users.create', 'display_name' => 'Create Users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users'],
        ],
        'branch' => [
            ['name' => 'branch.view', 'display_name' => 'View Branch'],
            ['name' => 'branch.create', 'display_name' => 'Create Branch'],
            ['name' => 'branch.edit', 'display_name' => 'Edit Branch'],
            ['name' => 'branch.delete', 'display_name' => 'Delete Branch'],
        ],
        'role' => [
            ['name' => 'roles.view', 'display_name' => 'View Role'],
            ['name' => 'roles.create', 'display_name' => 'Create Role'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Role'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Role'],
        ],
        'permissions' => [
            ['name' => 'permissions.view', 'display_name' => 'View Permissions'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions'],
            ['name' => 'permissions.edit', 'display_name' => 'Edit Permissions'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions'],
        ],
        'category' => [
            ['name' => 'category.view', 'display_name' => 'View Category'],
            ['name' => 'category.create', 'display_name' => 'Create Category'],
            ['name' => 'category.edit', 'display_name' => 'Edit Category'],
            ['name' => 'category.delete', 'display_name' => 'Delete Category'],
        ],
        'expense_category' => [
            ['name' => 'expense_category.view', 'display_name' => 'View Expense Category'],
            ['name' => 'expense_category.create', 'display_name' => 'Create Expense Category'],
            ['name' => 'expense_category.edit', 'display_name' => 'Edit Expense Category'],
            ['name' => 'expense_category.delete', 'display_name' => 'Delete Expense Category'],
        ],
        'expense' => [
            ['name' => 'expense.view', 'display_name' => 'View Expense'],
            ['name' => 'expense.create', 'display_name' => 'Create Expense'],
            ['name' => 'expense.edit', 'display_name' => 'Edit Expense'],
            ['name' => 'expense.delete', 'display_name' => 'Delete Expense'],
        ],
        'income' => [
            ['name' => 'income.view', 'display_name' => 'View Income'],
            ['name' => 'income.create', 'display_name' => 'Create Income'],
            ['name' => 'income.edit', 'display_name' => 'Edit Income'],
            ['name' => 'income.delete', 'display_name' => 'Delete Income'],
        ],
        'currency' => [
            ['name' => 'currency.view', 'display_name' => 'View Currency'],
            ['name' => 'currency.create', 'display_name' => 'Create Currency'],
            ['name' => 'currency.edit', 'display_name' => 'Edit Currency'],
            ['name' => 'currency.delete', 'display_name' => 'Delete Currency'],
        ],
        'client' => [
            ['name' => 'client.view', 'display_name' => 'View Client'],
            ['name' => 'client.create', 'display_name' => 'Create Client'],
            ['name' => 'client.edit', 'display_name' => 'Edit Client'],
            ['name' => 'client.delete', 'display_name' => 'Delete Client'],
            ['name' => 'client.statement', 'display_name' => ' Client Statement'],
        ],
        'client_transaction' => [
            ['name' => 'client_transaction.view', 'display_name' => 'View Client Transaction'],
            ['name' => 'client_transaction.create', 'display_name' => 'Create Client Transaction'],
            ['name' => 'client_transaction.edit', 'display_name' => 'Edit Client Transaction'],
            ['name' => 'client_transaction.delete', 'display_name' => 'Delete Client Transaction'],
            ['name' => 'client.receivable', 'display_name' => ' Client Receivable'],
            ['name' => 'client.payable', 'display_name' => ' Client Payable'],
        ],
        'cargo' => [
            ['name' => 'cargo.view', 'display_name' => 'View Cargo'],
            ['name' => 'cargo.create', 'display_name' => 'Create Cargo'],
            ['name' => 'cargo.edit', 'display_name' => 'Edit Cargo'],
            ['name' => 'cargo.delete', 'display_name' => 'Delete Cargo'],
            ['name' => 'cargo.details', 'display_name' => 'Cargo Details'],
            ['name' => 'branch.receivable', 'display_name' => 'Branch Receivable'],
        ],
        'payment' => [
            ['name' => 'payment.view', 'display_name' => 'View Payment'],
            ['name' => 'payment.create', 'display_name' => 'Create Payment'],
            ['name' => 'payment.edit', 'display_name' => 'Edit Payment'],
            ['name' => 'payment.delete', 'display_name' => 'Delete Payment'],
        ],
        'country' => [
            ['name' => 'country.view', 'display_name' => 'View Country'],
            ['name' => 'country.create', 'display_name' => 'Create Country'],
            ['name' => 'country.edit', 'display_name' => 'Edit Country'],
            ['name' => 'country.delete', 'display_name' => 'Delete Country'],
        ],
        'setting' => [
            ['name' => 'setting.view', 'display_name' => 'View Settings'],
            ['name' => 'setting.create', 'display_name' => 'Create Settings'],
            ['name' => 'setting.edit', 'display_name' => 'Edit Settings'],
            ['name' => 'setting.delete', 'display_name' => 'Delete Settings'],
        ],
        'account' => [
            ['name' => 'account.view', 'display_name' => 'View Account'],
            ['name' => 'account.create', 'display_name' => 'Create Account'],
            ['name' => 'account.edit', 'display_name' => 'Edit Account'],
            ['name' => 'account.delete', 'display_name' => 'Delete Account'],
        ],
        'account_transaction' => [
            ['name' => 'account_transaction.view', 'display_name' => 'View Account Transaction'],
            ['name' => 'account_transaction.create', 'display_name' => 'Create Account Transaction'],
            ['name' => 'account_transaction.edit', 'display_name' => 'Edit Account Transaction'],
            ['name' => 'account_transaction.delete', 'display_name' => 'Delete Account Transaction'],
        ],
        'account_transfer' => [
            ['name' => 'account_transfer.view', 'display_name' => 'View Account Transfer'],
            ['name' => 'account_transfer.create', 'display_name' => 'Create Account Transfer'],
            ['name' => 'account_transfer.edit', 'display_name' => 'Edit Account Transfer'],
            ['name' => 'account_transfer.delete', 'display_name' => 'Delete Account Transfer'],
        ],
        'staff_transaction' => [
            ['name' => 'staff_transaction.view', 'display_name' => 'View Staff Transaction'],
            ['name' => 'staff_transaction.create', 'display_name' => 'Create Staff Transaction'],
            ['name' => 'staff_transaction.edit', 'display_name' => 'Edit Staff Transaction'],
            ['name' => 'staff_transaction.delete', 'display_name' => 'Delete Staff Transaction'],
        ],
        'staff' => [
            ['name' => 'staff.view', 'display_name' => 'View Staff'],
            ['name' => 'staff.create', 'display_name' => 'Create Staff'],
            ['name' => 'staff.edit', 'display_name' => 'Edit Staff'],
            ['name' => 'staff.delete', 'display_name' => 'Delete Staff'],
        ],
        'staff_salary' => [
            ['name' => 'staff_salary.view', 'display_name' => 'View Staff Salary'],
            ['name' => 'staff_salary.create', 'display_name' => 'Create Staff Salary'],
            ['name' => 'staff_salary.edit', 'display_name' => 'Edit Staff Salary'],
            ['name' => 'staff_salary.delete', 'display_name' => 'Delete Staff Salary'],
        ],
        'document' => [
            ['name' => 'document.view', 'display_name' => 'View Document'],
            ['name' => 'document.create', 'display_name' => 'Create Document'],
            ['name' => 'document.edit', 'display_name' => 'Edit Document'],
            ['name' => 'document.delete', 'display_name' => 'Delete Document'],
            ['name' => 'document.restore', 'display_name' => 'Restore Document'],
            ['name' => 'document.forceDelete', 'display_name' => 'Force Delete Document'],
        ],
        'report' => [
            ['name' => 'report.expense', 'display_name' => 'Expense Report'],
            ['name' => 'report.income', 'display_name' => 'Income Report'],
            ['name' => 'report.cargo', 'display_name' => 'Cargo Report'],
            ['name' => 'report.account_log', 'display_name' => 'Account Log Report'],
            ['name' => 'report.due_clients', 'display_name' => 'Due Clients  Report'],
        ],
    ];

    // Loop through each permission group and create permissions
    foreach ($permissionGroups as $groupName => $permissions) {
        $this->createPermissionGroup($groupName, $permissions);
    }
}

protected function createPermissionGroup($groupName, $permissions)
{
    // Create or find permission group
    $g = (new PermissionGroup())->where('name', $groupName)->first();
    if (!$g) {
        $g = PermissionGroup::create([
            'name' => $groupName,
            'category' => 'admin',
        ]);
    }

    // Create permissions and sync with group
    foreach ($permissions as $permission) {
        $p = (new Permission())->where('name', $permission['name'])->first();
        if (!$p) {
            $p = Permission::create([
                'name' => $permission['name'],
                'display_name' => $permission['display_name']
            ]);
            $g->permissions()->sync($p->id, false);
        }
    }
}

}
