<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->realText(15),
            'supplier' => $this->faker->realText(15),
            'category' => $this->faker->realText(15),
            'qty' => $this->faker->randomNumber(3, false),
            'expiry' => $this->faker->unique()->dateTimeBetween('now', '30 years'),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'user_id' => 1
        ];
    }
}
