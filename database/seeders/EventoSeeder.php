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
                'titulo'    => 'Festa da Padroeira Nossa Senhora da Glória',
                'descricao' => 'Festa da padroeira celebrada em dois dias, na Igreja Ucraniana de Pitanga. '
                             . 'No sábado, 15 de agosto, tarde festiva a partir das 14h, com pastel, sonho, suspiro e bolos, '
                             . 'e Divina Liturgia em português às 19h. '
                             . 'No domingo, 16 de agosto, às 9h, carreata com veículos antigos levando a imagem da Padroeira, '
                             . 'sob responsabilidade do Pitanga Volks Club e Antigos, com saída do pátio da igreja. '
                             . 'Às 10h, Divina Liturgia, Benção Apostólica em ocasião à Padroeira, procissão no pátio com a imagem '
                             . 'e benção de veículos. '
                             . 'Ao meio-dia, almoço festivo com churrasco assado e desossado (R$ 70,00 o quilo), carne suína '
                             . '(R$ 35,00 o quilo), perohê, maionese, farofa, arroz, pão e salada. '
                             . 'Às 13h30, início do binguinho com prêmios, e venda de pastel, sonho, suspiro, bolo e bebidas '
                             . 'durante a tarde. '
                             . 'O conselho paroquial convida você e sua família.',
                'data'      => '2026-08-16',
                'horario'   => '09:00',
                'local'     => 'Paróquia Nossa Senhora da Glória',
                'imagem'    => 'images/eventos/festa-padroeira-2026.jpg',
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
