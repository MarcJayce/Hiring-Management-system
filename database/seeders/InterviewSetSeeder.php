<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InterviewSetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = ['IT', 'Marketing'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('interview_sets')->insert([
                'name' => $faker->words(3, true), 
                'category' => $faker->randomElement($categories),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
