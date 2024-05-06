<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 15) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'user_id' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                
                'phone_number' => $faker->phoneNumber,
                'status' => $faker->randomElement(['active', 'inactive']),
                'role_name' => $faker->randomElement(['admin', 'employee']),
                'avatar' => $faker->imageUrl(),
                'position' => $faker->jobTitle,
                $department = $faker->word,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Default password is 'password'
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}