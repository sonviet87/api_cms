<?php
namespace Database\Seeders;
use App\Constants\RolePermissionConst;
use App\Constants\UserConst;
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
        DB::table('permissions')->whereIn('user_role_id', [2, 3, 4])->delete();

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
        ]);

    }
}
