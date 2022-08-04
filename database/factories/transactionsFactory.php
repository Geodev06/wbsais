<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class transactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'items' => $this->faker->realText(15),
            'amount' => $this->faker->randomFloat(2, 1, 10000),
            'customer_amount' => $this->faker->randomFloat(2, 1, 600),
            'user_id' => 5,
            'created_at' => $this->faker->unique()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
