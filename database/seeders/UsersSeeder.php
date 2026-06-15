<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    private const DEFAULT_PASSWORD = '123456';

    private const ACTIVE_STATUS = 'A';

    private const FIRST_GENERATED_STUDENT_ID = 31;

    private const LAST_GENERATED_STUDENT_ID = 66;

    private const BASE_USERS = [
        1 => 'juan.gomez.admin',
        2 => 'maria.lopez.teacher',
        3 => 'luis.torres.teacher',
        4 => 'ana.rojas.teacher',
        5 => 'carlos.martinez.teacher',
        6 => 'laura.garcia.teacher',
        7 => 'miguel.hernandez.student',
        8 => 'isabel.jimenez.student',
        9 => 'diego.perez.student',
        10 => 'valentina.ramirez.student',
        11 => 'andres.flores.student',
        12 => 'camila.gutierrez.student',
        13 => 'javier.reyes.student',
        14 => 'gabriela.morales.student',
        15 => 'roberto.ortiz.student',
        16 => 'natalia.delgado.student',
        17 => 'fernando.sanchez.student',
        18 => 'monica.vargas.student',
        19 => 'eduardo.castro.student',
        20 => 'patricia.romero.student',
        21 => 'sofia.mendoza.student',
        22 => 'mateo.quispe.student',
        23 => 'lucia.navarro.student',
        24 => 'sebastian.vega.student',
        25 => 'daniela.campos.student',
        26 => 'ricardo.paredes.student',
        27 => 'elena.salazar.student',
        28 => 'piero.cardenas.student',
        29 => 'mariana.soto.student',
        30 => 'alonso.rivera.student',
    ];

    public function run(): void
    {
        $this->truncateUsers();

        DB::table('users')->insert($this->users());
    }

    private function truncateUsers(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function users(): array
    {
        $password = Hash::make(self::DEFAULT_PASSWORD);
        $timestamp = now();

        return collect($this->usernames())
            ->map(fn (string $username, int $personId) => [
                'person_id' => $personId,
                'username' => $username,
                'password' => $password,
                'status' => self::ACTIVE_STATUS,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])
            ->values()
            ->all();
    }

    private function usernames(): array
    {
        return self::BASE_USERS + $this->generatedStudentUsers();
    }

    private function generatedStudentUsers(): array
    {
        return collect(range(self::FIRST_GENERATED_STUDENT_ID, self::LAST_GENERATED_STUDENT_ID))
            ->mapWithKeys(fn (int $personId) => [
                $personId => "estudiante{$personId}.student",
            ])
            ->all();
    }
}
