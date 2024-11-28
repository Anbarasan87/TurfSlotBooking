<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turf;
use App\Models\User;

class TurfSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::all()->pluck('id')->toArray();

        Turf::factory()->count(5)->create([
            'owner_id' => function () use ($userIds) {
                return $this->faker->randomElement($userIds); 
            },
        ]);
    }
}
