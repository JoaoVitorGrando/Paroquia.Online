<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            [
                'titulo'    => 'Festa da Colheita',
                'descricao' => 'Celebração anual em ação de graças pela colheita. Com missa solene, apresentações culturais e almoço comunitário.',
                'data'      => '2026-07-20',
                'horario'   => '10:00',
                'local'     => 'Paróquia Nossa Senhora da Glória',
            ],
            [
                'titulo'    => 'Encontro de Catequese',
                'descricao' => 'Reunião semanal dos grupos de catequese para crianças e adolescentes.',
                'data'      => '2026-06-07',
                'horario'   => '14:00',
                'local'     => 'Salão Paroquial',
            ],
            [
                'titulo'    => 'Reunião do Grupo de Jovens',
                'descricao' => 'Encontro mensal do grupo de jovens da paróquia com dinâmicas, partilha e reflexão.',
                'data'      => '2026-06-14',
                'horario'   => '19:30',
                'local'     => 'Salão Paroquial',
            ],
            [
                'titulo'    => 'Apostolado da Oração',
                'descricao' => 'Reunião do grupo Apostolado da Oração com terço, leituras e partilha espiritual.',
                'data'      => '2026-06-21',
                'horario'   => '08:30',
                'local'     => 'Igreja Matriz',
            ],
            [
                'titulo'    => 'Festa Junina Paroquial',
                'descricao' => 'Festa junina com comidas típicas, quadrilha e barracas. Aberta para toda a comunidade.',
                'data'      => '2026-06-28',
                'horario'   => '16:00',
                'local'     => 'Pátio da Paróquia',
            ],
        ];

        // Evita duplicar dados ao rodar o seeder mais de uma vez.
        // A busca usa titulo + data; whereDate compara so a data, ignorando a hora
        // que o cast 'date' grava junto no banco.
        foreach ($eventos as $evento) {
            Evento::whereDate('data', $evento['data'])
                ->firstOrCreate(['titulo' => $evento['titulo']], $evento);
        }
    }
}
