<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 5),
            'turf_id' => $this->faker->numberBetween(1, 5),
            'booking_date' => $this->faker->date,
            'time_slot' => $this->faker->time,
            'status' => $this->faker->randomElement(['confirmed', 'canceled']),
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
