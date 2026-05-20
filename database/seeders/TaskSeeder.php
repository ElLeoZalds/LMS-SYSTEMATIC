<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'training_id' => 1,
            'title' => 'Laboratorio 1: Configuración de Entorno',
            'description' => 'Subir un PDF con las capturas de pantalla de su servidor local funcionando.',
            'due_date' => now()->addDays(7),
        ]);

        Task::create([
            'training_id' => 1,
            'title' => 'Proyecto Final: Avance 1',
            'description' => 'Compartir el enlace de su repositorio de GitHub con el avance del modelo relacional.',
            'due_date' => now()->addDays(14),
        ]);
    }
}