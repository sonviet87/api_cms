<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CategoryFP;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(PermissionSeeder::class);
       // $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(FPSeeder::class);
        $this->call(CategoryFPSeeder::class);
      /*  \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
        ]);
         \App\Models\User::factory(10)->create();*/


    }
}
