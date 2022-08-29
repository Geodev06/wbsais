<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

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
            'product_name' => $this->faker->realText(12),
            'supplier' => $this->faker->realText(10),
            'category' => $this->faker->realText(15),
            'qty' => $this->faker->randomNumber(3, false),
            'expiry' => $this->faker->unique()->dateTimeBetween('now', '30 years'),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'user_id' => 1,
            'inventory_id' => '$2y$10$0fVw.CJYaMxcDh/CoZsyD.EdK.o3vgx0pRR3qd65NwQiOm3q7.UEO'
        ];
    }
}
