<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fictional companies and vacancies for the midterm demonstration.
        $vacancies = [
            ['Laravel Developer', 'BluePeak Software', 'Tbilisi', 3200, 'full-time', '2026-11-01'],
            ['Frontend Developer', 'BluePeak Software', 'Kutaisi', 2800, 'full-time', '2026-11-02'],
            ['QA Engineer', 'Riverstone Digital', 'Batumi', 2400, 'full-time', '2026-11-03'],
            ['Product Designer', 'Cedar Studio', 'Tbilisi', 3000, 'full-time', '2026-11-04'],
            ['Data Analyst', 'Summit Analytics', 'Kutaisi', 2600, 'full-time', '2026-11-05'],
            ['IT Support Specialist', 'Harbor Networks', 'Batumi', 1800, 'full-time', '2026-11-06'],
            ['Project Coordinator', 'Orchard Systems', 'Tbilisi', 2200, 'full-time', '2026-11-07'],
            ['DevOps Engineer', 'Atlas Cloud', 'Kutaisi', 4200, 'full-time', '2026-11-08'],
            ['Junior PHP Developer', 'Maple Web', 'Kutaisi', 1400, 'part-time', '2026-11-01'],
            ['Content Editor', 'Paperkite Media', 'Tbilisi', 1100, 'part-time', '2026-11-02'],
            ['Graphic Designer', 'Cedar Studio', 'Batumi', 1500, 'part-time', '2026-11-03'],
            ['Customer Support Agent', 'Harbor Networks', 'Kutaisi', 1000, 'part-time', '2026-11-04'],
            ['Social Media Assistant', 'Paperkite Media', 'Tbilisi', 1200, 'part-time', '2026-11-05'],
            ['QA Tester', 'Riverstone Digital', 'Batumi', 1300, 'part-time', '2026-11-06'],
            ['Research Assistant', 'Summit Analytics', 'Kutaisi', 1600, 'part-time', '2026-11-07'],
            ['Office Administrator', 'Orchard Systems', 'Tbilisi', 1100, 'part-time', '2026-11-08'],
            ['Senior Laravel Developer', 'Atlas Cloud', 'Tbilisi', 5000, 'remote', '2026-11-02'],
            ['Full Stack Developer', 'Maple Web', 'Kutaisi', 3800, 'remote', '2026-11-03'],
            ['Technical Writer', 'BluePeak Software', 'Batumi', 2500, 'remote', '2026-11-04'],
            ['UX Researcher', 'Cedar Studio', 'Tbilisi', 3000, 'remote', '2026-11-05'],
            ['Data Engineer', 'Summit Analytics', 'Kutaisi', 4400, 'remote', '2026-11-06'],
            ['Cloud Support Engineer', 'Atlas Cloud', 'Batumi', 3200, 'remote', '2026-11-07'],
            ['Digital Marketing Specialist', 'Paperkite Media', 'Tbilisi', 2700, 'remote', '2026-11-08'],
            ['Mobile Developer', 'Riverstone Digital', 'Kutaisi', 4000, 'remote', '2026-11-09'],
        ];

        foreach ($vacancies as [$position, $company, $city, $salary, $employmentType, $postedAt]) {
            Vacancy::updateOrCreate(
                ['position' => $position, 'company' => $company],
                [
                    'city' => $city,
                    'salary_from' => $salary,
                    'employment_type' => $employmentType,
                    'posted_at' => $postedAt,
                ],
            );
        }
    }
}
