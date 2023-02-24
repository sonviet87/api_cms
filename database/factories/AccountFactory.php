<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'legal_name' => fake()->name(),
            'industry' => fake()->sentence($nbWords = 2, $variableNbWords = true),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'legal_address' => fake()->address(),
            'email' => fake()->email(),
            'city' => fake()->city(),
            'user_id' =>User::all()->random()->id
        ];


    }
}
