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
        $role = Role::create(['name' => RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_ADMIN],'guard_name' => 'api']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $admin->assignRole([$role->name]);
        //create CEO
        User::insert([
            'name' => 'CEO',
            'username' =>'ceo',
            'email' => 'ceo@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
        ]);
        $ceo = User::find(2);
        $roleCEO = Role::create(['name' => RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_CEO],'guard_name' => 'api']);

        $roleCEO->syncPermissions($permissions);
        $ceo->assignRole([$roleCEO->name]);
       // $admin->givePermissionTo($permissionsRole);
        User::insert([
            'name' => 'Quản lý',
            'username' =>'agency',
            'email' => 'agency@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            //'role_id' => RolePermissionConst::ROLE_AGENCY
        ]);
        $role1 = Role::create(['name' => RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_Manager],'guard_name' => 'api']);
        $manager = User::find(3);
        $permissions1 = [1,2,5,6,9,10];
        $role1->syncPermissions($permissions1);
        $manager->assignRole([$role1->name]);
        User::insert([
            'name' => 'Sale 1',
            'username' =>'sale1',
            'email' => 'sale1@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,

        ]);
        $role2 = Role::create(['name' =>  RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE],'guard_name' => 'api']);
        $client = User::find(4);
        $permissions2 = [1,5,9];
        $role2->syncPermissions($permissions2);
        $client->assignRole([$role2->name]);

        User::insert([
            'name' => 'Sale 2',
            'username' =>'sale2',
            'email' => 'sale2@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,

        ]);
        $client2 = User::find(5);
        $client2->assignRole([$role2->name]);
    }
}
