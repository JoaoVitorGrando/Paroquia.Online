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
                'titulo'    => 'Encontro Paroquial de Catequistas e Crianças da Catequese',
                'descricao' => 'Recepção e café da manhã a partir das 8h30, na Paróquia Nossa Senhora da Glória. '
                             . 'Palestrante para os catequistas: Padre Inácio Malinoski, OSBM. '
                             . 'Palestrantes para as crianças: seminaristas de Ivaí. Patrocínio da paróquia. '
                             . 'Confirme a presença até 17/10 com os padres ou pelo telefone e WhatsApp da secretaria. '
                             . '"Deixai vir a mim os pequeninos, pois é deles o reino dos céus" (Mt 19,14).',
                'data'      => '2026-10-24',
                'horario'   => '08:30',
                'local'     => 'Paróquia Nossa Senhora da Glória',
                'imagem'    => 'images/eventos/encontro-catequistas-2026.jpg',
            ],
            [
                'titulo'    => 'Festa da Colheita',
                'descricao' => 'Celebração anual em ação de graças pela colheita. Com missa solene, apresentações culturais e almoço comunitário.',
                'data'      => '2026-08-16',
                'horario'   => '10:00',
                'local'     => 'Paróquia Nossa Senhora da Glória',
            ],
            [
                'titulo'    => 'Encontro de Catequese',
                'descricao' => 'Encontro semanal dos grupos de catequese, aos sábados, das 8h30 às 11h30.',
                'data'      => '2026-06-06',
                'horario'   => '08:30',
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
