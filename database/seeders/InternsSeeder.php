<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use App\Models\JobPosition;
use Carbon\Carbon;

class InternsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();


        $internPositionIds = JobPosition::whereRaw('TRIM(application_type) = ?', ['Intern'])->pluck('id');


        if ($internPositionIds->isEmpty()) {
            $this->command->warn('No internship positions found. Skipping InternsSeeder.');
            return;
        }

        foreach (range(1, 10) as $i) {
            $applicantId = DB::table('applicant_details')->insertGetId([
                'position_id' => $faker->randomElement($internPositionIds),
                'certifications' => $faker->sentence(),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'city' => $faker->city,
                'status' => 'For Screening',
                'email_status' => null,
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
                'offer_date' => null,
                'start_date' => null,
                'hiring_manager' => null,
                'department' => 'IT',
                'hiring_date' => null,
                'offer_end_date' => null,
            ]);

            DB::table('skills_experiences')->insert([
                'applicant_id' => $applicantId,
                'skills' => json_encode($faker->words(5)),
                'volunteer_experience' => $faker->sentence,
                'part_time_jobs' => $faker->sentence,
                'extracurricular' => $faker->sentence,
                'portfolio_url' => $faker->url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('internship_specifics')->insert([
                'applicant_id' => $applicantId,
                'internship_type' => $faker->randomElement(['voluntary', 'academic']),
                'desired_start_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                //'desired_end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'hours_required' => $faker->numberBetween(200, 600),
                'weekly_availability' => 'Monday to Friday, 9 AM – 5 PM',
                'internship_goals' => $faker->paragraph,
                'internship_interest' => $faker->paragraph,
                'why_hire' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('education')->insert([
                'applicant_id' => $applicantId,
                'university' => $faker->randomElement(['Mapúa University', 'De La Salle University', 'University of Santo Tomas']),
                'major_minor' => 'BS Information Technology',
                'expected_graduation_date' => Carbon::now()->addYear()->format('Y-m-d'),
                'academic_projects' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('other_information')->insert([
                'applicant_id' => $applicantId,
                'resume' => $faker->url,
                'linkedin' => $faker->optional()->url,
                'referral_source' => $faker->randomElement(['LinkedIn', 'Facebook', 'Referral', 'Company Website']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $availabilityTemplates = [
                'Morning',
                'Afternoon',
                'Anytime',
            ];
            
            for ($d = 1; $d <= 3; $d++) {
                DB::table('availability_dates')->insert([
                    'applicant_id' => $applicantId,
                    'available_date' => Carbon::now()->addDays($d * 2)->format('F j, Y') . ' - ' . $faker->randomElement($availabilityTemplates),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
