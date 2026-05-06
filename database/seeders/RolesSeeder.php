<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Teacher',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Student',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}