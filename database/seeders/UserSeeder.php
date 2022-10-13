<?php
namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Constants\UserConst;
use App\Constants\RolePermissionConst;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::insert([
            'name' => 'admin',
            'username' =>'admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            //'role_id' => RolePermissionConst::ROLE_ADMIN
        ]);
        $admin = User::find(1);
        $role = Role::create(['name' => 'admin','guard_name' => 'api']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);
        $permissionsRole = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'account-list',
            'account-create',
            'account-edit',
            'account-delete'
        ];
        $admin->assignRole([$role->name]);
        $admin->givePermissionTo($permissionsRole);
        User::insert([
            'name' => 'Quản lý',
            'username' =>'agency',
            'email' => 'agency@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            //'role_id' => RolePermissionConst::ROLE_AGENCY
        ]);
        $role1 = Role::create(['name' => 'manager','guard_name' => 'api']);
        $manager = User::find(2);
        $permissions1 = [1,2,5,6,9,10];
        $role1->syncPermissions($permissions1);
        $manager->assignRole([$role1->name]);
        User::insert([
            'name' => 'Người dùng',
            'username' =>'client',
            'email' => 'client@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            //'role_id' => RolePermissionConst::ROLE_CLIENT
        ]);
        $role2 = Role::create(['name' => 'client','guard_name' => 'api']);
        $client = User::find(3);
        $permissions2 = [1,5,9];
        $role2->syncPermissions($permissions2);
        $client->assignRole([$role2->name]);
    }
}
