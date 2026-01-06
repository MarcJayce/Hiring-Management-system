<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class JobPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $employmentTypes = ['Intern', 'Full-Time Employee', 'Part-Time Employee'];
        $workSetups = ['On-site', 'Hybrid', 'Work From Home'];
        $statuses = ['Active', 'Closed'];

       
        $jobTitlesIT = [
            'Software Developer',
            'System Analyst',
            'Network Engineer',
            'Database Administrator',
            'IT Support Specialist',
            'Cybersecurity Analyst',
            'DevOps Engineer',
            'Web Developer',
            'QA Engineer',
        ];

        $jobTitlesMarketing = [
            'Marketing Manager',
            'Digital Marketing Specialist',
            'SEO Analyst',
            'Content Strategist',
            'Brand Manager',
            'Social Media Coordinator',
            'Market Research Analyst',
            'Product Marketing Specialist',
            'Email Marketing Specialist',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $applicationType = $faker->randomElement($employmentTypes);
            $department = $faker->randomElement(['IT', 'Marketing']);

            
            if ($department === 'IT') {
                $jobTitle = $faker->randomElement($jobTitlesIT);
            } else {
                $jobTitle = $faker->randomElement($jobTitlesMarketing);
            }

           
            if ($applicationType === 'Intern') {
                $jobTitle .= ' Intern';
            }

            $startDate = $faker->dateTimeBetween('+1 week', '+1 month');
            $endDate = (clone $startDate)->modify('+6 months');

            DB::table('job_positions')->insert([
                'position_title' => $jobTitle,
                'department' => $department,
                'work_setup' => $faker->randomElement($workSetups),
                'reports_to' => $faker->name,
                'job_duration' => $faker->numberBetween(3, 12) . ' Months',
                'work_hours' => 'Monday to Friday, ' . $faker->time('g:i A') . ' - ' . $faker->time('g:i A'),
                'compensation' => $faker->randomElement(['Allowance Provided', 'No Allowance Provided']),
                'position_description' => $faker->paragraph,
                'key_responsibilities' => '<ol><li>' . implode('</li><li>', $faker->sentences(5)) . '</li></ol>',
                'benefits' => '<ol><li>' . implode('</li><li>', $faker->sentences(5)) . '</li></ol>',
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'availability' => $faker->numberBetween(1, 10),
                'status' => $faker->randomElement($statuses),
                'application_type' => $applicationType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
