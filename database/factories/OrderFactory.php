<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'user_address_id' => UserAddress::factory(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'paid', 'cancelled']),
            'session_id' => $this->faker->uuid(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
