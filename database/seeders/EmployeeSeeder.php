<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get job positions where application_type is NOT 'Intern'
        $jobPositionIds = DB::table('job_positions')
            ->where('application_type', '!=', 'Intern')
            ->pluck('id')
            ->toArray();

        
        for ($i = 0; $i < 10; $i++) {
            $positionId = $faker->randomElement($jobPositionIds);

            $applicantId = DB::table('applicant_details')->insertGetId([
                'position_id' => $positionId,
                'certifications' => $faker->sentence(),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'city' => $faker->city,
                'status' => 'For Screening',
                'email_status' => 'Unverified',
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
                'offer_date' => null,
                'start_date' => null,
                'hiring_manager' => $faker->name,
                'department' => $faker->randomElement(['IT', 'Marketing', 'HR', 'Finance', 'Operations']),
                'hiring_date' => null,
                'offer_end_date' => null,
            ]);

           
            $educationCount = rand(1, 2);
            for ($j = 0; $j < $educationCount; $j++) {
                DB::table('employee_educations')->insert([
                    'applicant_id' => $applicantId,
                    'degree_earned' => $faker->randomElement(['Bachelor of Science', 'Master of Science', 'Diploma', 'Associate Degree']),
                    'university_name' => $faker->company . ' University',
                    'graduation_date' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            
            DB::table('professional_experiences')->insert([
                'applicant_id' => $applicantId,
                'company_name' => $faker->company,
                'job_title' => $faker->jobTitle,
                'start_date' => $faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
                'end_date' => optional($faker->optional()->dateTimeBetween('-1 year', 'now'))->format('Y-m-d'),
                'responsibilities' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            DB::table('job_specifics')->insert([
                'applicant_id' => $applicantId,
                'desired_salary' => $faker->randomFloat(2, 15000, 60000),
                'available_date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'job_interest' => $faker->paragraph,
                'why_hire' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

           
            DB::table('skills_abilities')->insert([
                'applicant_id' => $applicantId,
                'technical_skills' => json_encode(json_encode([
                    "Content Creation",
                    "SEO & SEM",
                    "Marketing Research and Analysis",
                    "Brand Management"
                ])),

                'industry_knowledge' => $faker->sentence,
                'soft_skills' => json_encode(json_encode([
                    "Communication",
                    "Teamwork",
                    "Time Management",
                    "Creativity",
                    "Attention to Detail"
                ])),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            DB::table('other_information')->insert([
                'applicant_id' => $applicantId,
                'resume' => $faker->uuid . '.pdf',
                'linkedin' => $faker->optional()->url,
                'referral_source' => $faker->randomElement(['Online Job Portal', 'Employee Referral', 'Social Media', 'Company Website']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
