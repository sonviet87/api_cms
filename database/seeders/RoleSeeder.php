<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Admin',
            'description' => 'Highest level permission',
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Agency',
            'description' => 'Can sell tickets for customers',
        ]);
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'Client',
            'description' => 'Can not do anything',
        ]);

    }
}
