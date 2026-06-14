<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Assistant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Academic Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Content Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Support',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Auditor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Evaluator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reporter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Viewer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Editor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Demo Role',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
