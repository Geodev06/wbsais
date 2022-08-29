<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->realText(10),
            'description' => $this->faker->realText(30),
            'amount' => $this->faker->randomFloat(2, 1, 10000),
            'user_id' => 1,
            'inventory_id' => '$2y$10$gTmAPd.O3hn9GLaN.HGgUuBSnFchoawfPGZqBy0buxkuFhhsa8bQS',
            'created_at' => $this->faker->unique()->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
