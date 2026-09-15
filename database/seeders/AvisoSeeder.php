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
                'titulo'   => 'Inscrições abertas para a Catequese 2026',
                'conteudo' => 'As inscrições para a catequese 2026 estão abertas. Os encontros acontecem aos sábados, das 8h30 às 11h30. Procure a secretaria paroquial ou fale conosco pelo WhatsApp.',
                'destaque' => true,
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
