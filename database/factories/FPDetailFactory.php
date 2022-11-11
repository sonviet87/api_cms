<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\FP;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FPDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'category_id' => Category::all()->random()->id,
            'fp_id' => FP::all()->random()->id,
            'supplier_id' => Supplier::all()->random()->id,
            'qty' => fake()->randomNumber(),
            'price_buy' =>fake()->numberBetween(2000000,3000000),
            'price_sell' =>fake()->numberBetween(3000000,7000000),
            'profit' =>fake()->randomNumber() ,

        ];


    }
}
