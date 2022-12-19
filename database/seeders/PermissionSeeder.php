<?php
namespace Database\Seeders;
use App\Constants\RolePermissionConst;
use App\Constants\UserConst;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /* DB::table('permissions')->whereIn('user_role_id', [2, 3, 4])->delete();

        DB::table('permissions')->insert([
            'user_role_id' => RolePermissionConst::ROLE_AGENCY,
            'type' => 'role',
            'controller' => 'User',
            'action' => '["index", "store", "show", "update", "delete"]',
        ]);


        DB::table('permissions')->insert([
            'user_role_id' => RolePermissionConst::ROLE_CLIENT,
            'type' => 'role',
            'controller' => 'Category',
            'action' => '["getAllPaginate"]',
        ]);*/
        $permissions = [
            'dashboard-list',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'contact-list',
            'contact-create',
            'contact-edit',
            'contact-delete',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'account-list',
            'account-create',
            'account-edit',
            'account-delete',
            'fp-list',
            'fp-approved-manager',
            'fp-approved-sale',
            'fp-create',
            'fp-edit',
            'fp-delete',
            'fp-is-sale',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission,'guard_name' => 'api']);
        }

    }
}
