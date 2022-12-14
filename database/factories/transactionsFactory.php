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
            'no_of_items' => $this->faker->randomNumber(3),
            'user_id' => 1,
            'inventory_id' => '$2y$10$gTmAPd.O3hn9GLaN.HGgUuBSnFchoawfPGZqBy0buxkuFhhsa8bQS',
            'created_at' => $this->faker->unique()->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
