<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PeopleSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            RoleUserSeeder::class,
            SpecialtiesSeeder::class,
            TeacherSpecialtiesSeeder::class,
            CoursesSeeder::class,
            ModulesSeeder::class,
            TrainingsSeeder::class,
            AnnouncementsSeeder::class,
            PaymentMethodsSeeder::class,
            EnrollmentsSeeder::class,
            PaymentsSeeder::class,
            AssessmentsSeeder::class,
            QuestionsSeeder::class,
            AlternativesSeeder::class,
            ContentsSeeder::class,
            ProgressSeeder::class,
            SchedulesSeeder::class,
            AttendancesSeeder::class,
            AssessmentAttemptsSeeder::class,
            TaskSeeder::class,
            TaskSubmissionSeeder::class,
        ]);
    }
}
