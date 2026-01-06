<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $questionTypes = ['Behavioral', 'Technical'];

        
        $interviewSets = DB::table('interview_sets')->get();

        foreach ($interviewSets as $set) {
            
            $numQuestions = rand(3, 5);

            for ($i = 0; $i < $numQuestions; $i++) {
                DB::table('interview_questions')->insert([
                    'question_text' => $faker->sentence(rand(6, 12)),
                    'set_id' => $set->id,
                    'question_type' => $faker->randomElement($questionTypes),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
