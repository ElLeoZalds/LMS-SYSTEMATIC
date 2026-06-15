<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call($this->userBaseSeeders());
        $this->call($this->academicStructureSeeders());
        $this->call($this->paymentAndEnrollmentSeeders());
        $this->call($this->academicContentSeeders());
        $this->call($this->classControlSeeders());
        $this->call($this->trackingSeeders());
    }

    private function userBaseSeeders(): array
    {
        return [
            PeopleSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            RoleUserSeeder::class,
        ];
    }

    private function academicStructureSeeders(): array
    {
        return [
            SpecialtiesSeeder::class,
            TeacherSpecialtiesSeeder::class,
            CoursesSeeder::class,
            TrainingsSeeder::class,
            AnnouncementsSeeder::class,
        ];
    }

    private function paymentAndEnrollmentSeeders(): array
    {
        return [
            PaymentMethodsSeeder::class,
            EnrollmentsSeeder::class,
            PaymentsSeeder::class,
        ];
    }

    private function academicContentSeeders(): array
    {
        return [
            AssessmentsSeeder::class,
            QuestionsSeeder::class,
            AlternativesSeeder::class,
            ContentsSeeder::class,
            ProgressSeeder::class,
        ];
    }

    private function classControlSeeders(): array
    {
        return [
            SchedulesSeeder::class,
            AttendancesSeeder::class,
        ];
    }

    private function trackingSeeders(): array
    {
        return [
            AssessmentAttemptsSeeder::class,
            TaskSeeder::class,
            TaskSubmissionSeeder::class,
        ];
    }
}
