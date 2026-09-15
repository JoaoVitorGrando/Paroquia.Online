<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Missa;

class MissaSeeder extends Seeder
{
    public function run(): void
    {
        $celebracoes = [
            ['dia_semana' => 'Domingo',      'horario' => '08:30', 'local' => 'Igreja Matriz', 'observacao' => null],
            ['dia_semana' => 'Quarta feira', 'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => 'Novena'],
            ['dia_semana' => 'Sábado',       'horario' => '19:00', 'local' => 'Igreja Matriz', 'observacao' => null],
        ];

        // Evita duplicar dados ao rodar o seeder mais de uma vez
        foreach ($celebracoes as $celebracao) {
            Missa::firstOrCreate(
                ['dia_semana' => $celebracao['dia_semana'], 'horario' => $celebracao['horario']],
                array_merge($celebracao, ['ativo' => true])
            );
        }
    }
}
