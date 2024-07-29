<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => \App\Models\User::factory(), // Assuming you have a User factory
            'order_date' => $this->faker->dateTime(),
            'shipping_address' => $this->faker->address(),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'shipping_method' => $this->faker->randomElement(['JNT', 'Lazada', 'Grab']),
            'tracking_number' => $this->faker->ean8(),
            'shipping_status' => $this->faker->randomElement(['shipped', 'pending', 'delivered']),
        ];
    }
}
