<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aviso;

class AvisoSeeder extends Seeder
{
    public function run(): void
    {
        $avisos = [
            [
                'titulo'   => 'Inscriçoes abertas para a Catequese 2026',
                'conteudo' => 'As inscrições para a catequese 2026 estão abertas. Procure a secretaria paroquial para mais informações.',
                'destaque' => true,
            ],
            [
                'titulo'   => 'Festa da Colheita confirmada para julho',
                'conteudo' => 'A tradicional Festa da Colheita está confirmada para o dia 20 de julho. Voluntários podem se inscrever pelo site.',
                'destaque' => false,
            ],
            [
                'titulo'   => 'Novo horário de atendimento da secretaria',
                'conteudo' => 'A secretaria paroquial atenderá de segunda a sexta, das 09h às 12h e das 14h às 17h.',
                'destaque' => false,
            ],
        ];

        // Evita duplicar dados ao rodar o seeder mais de uma vez
        foreach ($avisos as $aviso) {
            Aviso::firstOrCreate(
                ['titulo' => $aviso['titulo']],
                $aviso
            );
        }
    }
}
