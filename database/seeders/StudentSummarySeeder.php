<?php

namespace Database\Seeders;

use App\Models\StudentSummary;
use Illuminate\Database\Seeder;

class StudentSummarySeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'id_number' => '2021001',
                'full_name' => 'Juan Dela Cruz',
                'course_year' => 'BS Computer Science - 3rd Year',
                'email' => 'juan.delacruz@student.example.com',
            ],
            [
                'id_number' => '2021002',
                'full_name' => 'Maria Santos',
                'course_year' => 'BS Information Technology - 2nd Year',
                'email' => 'maria.santos@student.example.com',
            ],
            [
                'id_number' => '2021003',
                'full_name' => 'Pedro Reyes',
                'course_year' => 'BS Computer Engineering - 4th Year',
                'email' => 'pedro.reyes@student.example.com',
            ],
            [
                'id_number' => '2021004',
                'full_name' => 'Ana Garcia',
                'course_year' => 'BS Information Systems - 1st Year',
                'email' => 'ana.garcia@student.example.com',
            ],
            [
                'id_number' => '2021005',
                'full_name' => 'Carlos Lopez',
                'course_year' => 'BS Software Engineering - 3rd Year',
                'email' => 'carlos.lopez@student.example.com',
            ],
        ];

        foreach ($students as $student) {
            StudentSummary::create($student);
        }
    }
}
