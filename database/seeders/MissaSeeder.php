<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Missa;

class MissaSeeder extends Seeder
{
    public function run(): void
    {
        $missas = [
            ['dia_semana' => 'Segunda feira', 'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => null],
            ['dia_semana' => 'Quarta feira', 'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => null],
            ['dia_semana' => 'Sexta feira',  'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => null],
            ['dia_semana' => 'Sábado',        'horario' => '18:00', 'local' => 'Igreja Matriz', 'observacao' => null],
            ['dia_semana' => 'Domingo',       'horario' => '09:00', 'local' => 'Igreja Matriz', 'observacao' => 'Missa solene'],
            ['dia_semana' => 'Domingo',       'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => null],
        ];

        // Evita duplicar dados ao rodar o seeder mais de uma vez
        foreach ($missas as $missa) {
            Missa::firstOrCreate(
                ['dia_semana' => $missa['dia_semana'], 'horario' => $missa['horario']],
                array_merge($missa, ['ativo' => true])
            );
        }
    }
}
