<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 15) as $index) {
            DB::table('employees')->insert([
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'birth_date' => $faker->date(),
                'gender' => $faker->randomElement(['male', 'female']),
                'employee_id' => $faker->unique()->randomNumber(),
                'company' => $faker->company,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}