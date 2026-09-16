<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'nome'            => 'Grupo de Jovens',
                'descricao'       => 'Grupo voltado para jovens da comunidade com encontros de fé, reflexão e convivência.',
                'responsavel'     => 'Pe. Ivan',
                'dia_reuniao'     => 'Sábado',
                'horario_reuniao' => '19:00',
                'local'           => 'Salão Paroquial',
                'imagem'          => 'images/grupo-de-jovens/logo-grupodejovens.png',
            ],
            [
                'nome'            => 'Grupo de Dança Ucraniana',
                'descricao'       => 'Preservação da cultura ucraniana através da dança tradicional. Aberto para todas as idades.',
                'responsavel'     => 'Olena Petrenko',
                'dia_reuniao'     => 'Quarta feira',
                'horario_reuniao' => '19:30',
                'local'           => 'Salão Paroquial',
                'imagem'          => 'images/imagens-grupo-danca/logo-grupodedancas.png',
            ],
            [
                'nome'            => 'Catequese',
                'descricao'       => 'Formação religiosa para crianças e adolescentes que desejam receber os sacramentos.',
                'responsavel'     => 'Marta Kovalenko',
                'dia_reuniao'     => 'Sábado',
                'horario_reuniao' => '08:30',
                'local'           => 'Sala de Catequese',
                'imagem'          => 'images/catequese/logo-catequese.png',
            ],
        ];

        // Atualiza os dados a cada execução (mantém a foto/infos em dia sem duplicar)
        foreach ($grupos as $grupo) {
            Grupo::updateOrCreate(
                ['nome' => $grupo['nome']],
                array_merge($grupo, ['ativo' => true])
            );
        }
    }
}
