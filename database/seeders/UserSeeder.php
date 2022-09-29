<?php
namespace Database\Seeders;
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
            'role_id' => RolePermissionConst::ROLE_ADMIN
        ]);
        User::insert([
            'name' => 'agency',
            'username' =>'agency',
            'email' => 'agency@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            'role_id' => RolePermissionConst::ROLE_AGENCY
        ]);

        User::insert([
            'name' => 'client',
            'username' =>'client',
            'email' => 'client@yopmail.com',
            'password' => Hash::make('123456'),
            'phone' => Str::random(10),
            'status' => UserConst::STATUS_ACTIVE,
            'role_id' => RolePermissionConst::ROLE_CLIENT
        ]);
    }
}
