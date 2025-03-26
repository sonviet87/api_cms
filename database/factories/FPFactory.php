<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FPFactory extends Factory
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
            'account_id' => Account::all()->random()->id,
            'contact_id' => Contact::all()->random()->id,
            'user_assign'=> User::all()->random()->id,
            'shipping_charges' => fake()->randomNumber(),
            'user_id' =>User::all()->random()->id,
            'user_assign' =>User::all()->random()->id,
            'tax' => fake()->numberBetween(200000,300000),
            'status' => fake()->numberBetween(1,4)
        ];


    }
}
