<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@student.example.com',
                'student_id' => '2021001',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@student.example.com',
                'student_id' => '2021002',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Pedro Reyes',
                'email' => 'pedro.reyes@student.example.com',
                'student_id' => '2021003',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ana Garcia',
                'email' => 'ana.garcia@student.example.com',
                'student_id' => '2021004',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Carlos Lopez',
                'email' => 'carlos.lopez@student.example.com',
                'student_id' => '2021005',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($students as $student) {
            User::create($student);
        }
    }
}
