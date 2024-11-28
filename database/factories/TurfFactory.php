<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Turf;

class TurfFactory extends Factory
{
    protected $model = Turf::class;

    public function definition()
    {
        return [
            'name' => 'Turf ' . $this->faker->unique()->numberBetween(1, 5),
            'location' => $this->faker->address,
            'image' => 'default.jpg',
            'sport_type' => $this->faker->randomElement(['Football', 'Cricket', 'Hockey']),
            'price_per_hour' => $this->faker->randomFloat(2, 50, 200),
            'availability' => json_encode(['Available','Unavailable']),
            'owner_id' => 1, // Use existing user ID
        ];
    }
}
