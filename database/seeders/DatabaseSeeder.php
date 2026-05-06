<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            // USERS BASE
            PeopleSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            RoleUserSeeder::class,

            // ACADEMIC STRUCTURE
            SpecialtiesSeeder::class,
            TeacherSpecialtiesSeeder::class,
            CoursesSeeder::class,
            TrainingsSeeder::class,

            // PAYMENTS & ENROLLMENT
            PaymentMethodsSeeder::class,
            EnrollmentsSeeder::class,
            PaymentsSeeder::class,

            // ACADEMIC CONTENT
            AssessmentsSeeder::class,
            QuestionsSeeder::class,
            AlternativesSeeder::class,
            ContentsSeeder::class,
            ProgressSeeder::class,

            // CLASS CONTROL
            SchedulesSeeder::class,
            AttendancesSeeder::class,

            // EXAMS TRACKING
            AssessmentAttemptsSeeder::class,
        ]);
    }
}