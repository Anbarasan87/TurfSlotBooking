<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Seed 5 users
        for ($i = 1; $i <= 11; $i++) {
            User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => bcrypt('password'), // Default password for all users
                'role' => 'owner', // You can modify roles as needed
            ]);
        }
    }
}

