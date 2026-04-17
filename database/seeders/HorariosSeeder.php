<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('horarios')->insert([
            [
                'idcapacitacion' => 1,
                'fecha' => now()->addDays(1)->toDateString(),
                'inicio' => '09:00:00'
            ],
            [
                'idcapacitacion' => 2,
                'fecha' => now()->addDays(2)->toDateString(),
                'inicio' => '14:00:00'
            ],
            [
                'idcapacitacion' => 3,
                'fecha' => now()->addDays(3)->toDateString(),
                'inicio' => '10:00:00'
            ],
            [
                'idcapacitacion' => 4,
                'fecha' => now()->addDays(4)->toDateString(),
                'inicio' => '16:00:00'
            ],
            [
                'idcapacitacion' => 5,
                'fecha' => now()->addDays(5)->toDateString(),
                'inicio' => '08:30:00'
            ]
        ]);
    }
}
