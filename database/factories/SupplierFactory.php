<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company' => fake()->name(),
            'address' => fake()->address(),
            'mst' => fake()->randomNumber(),
            'account' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'user_id' =>User::all()->random()->id
        ];
    }
}
