<?php

/*
|--------------------------------------------------------------------------
| Dados institucionais da paróquia
|--------------------------------------------------------------------------
|
| Ponto único de verdade para nome, CNPJ, endereço, telefones, e-mail e
| redes sociais. As telas leem daqui via config('paroquia.<chave>'), de modo
| que uma mudança de telefone ou de endereço é feita em um lugar só, ou no
| .env, sem tocar em código.
|
*/

return [

    'nome'      => env('PAROQUIA_NOME', 'Paróquia Nossa Senhora da Glória'),
    'nome_curto' => 'Paróquia N. S. da Glória',
    'rito'      => 'Igreja Católica Ucraniana · Rito Bizantino',
    'cnpj'      => env('PAROQUIA_CNPJ', '11.543.643/0006-28'),

    // Contato
    'telefone'  => env('PAROQUIA_TELEFONE', '(42) 3646-1336'),
    'whatsapp'  => env('PAROQUIA_WHATSAPP', '5542998204618'),
    'celular'   => env('PAROQUIA_CELULAR', '(42) 99820-4618'),
    'email'     => env('PAROQUIA_EMAIL', 'pnsgpitanga@gmail.com'),

    // Endereço
    'endereco' => [
        'logradouro' => env('PAROQUIA_LOGRADOURO', 'Rua Conselheiro Zacarias'),
        'numero'     => env('PAROQUIA_NUMERO', '890'),
        'bairro'     => env('PAROQUIA_BAIRRO', 'Centro'),
        'cep'        => env('PAROQUIA_CEP', '85200-053'),
        'cidade'     => env('PAROQUIA_CIDADE', 'Pitanga'),
        'uf'         => env('PAROQUIA_UF', 'PR'),
        'estado'     => 'Paraná',
    ],

    // Atendimento da secretaria
    'atendimento' => [
        'dias'    => 'Segunda a sexta',
        'horario' => '09h às 12h e 14h às 17h',
    ],

    // Redes sociais e mapa
    'redes' => [
        'facebook'        => env('PAROQUIA_FACEBOOK', 'https://www.facebook.com/pnsgpitanga/'),
        'instagram'       => env('PAROQUIA_INSTAGRAM', 'https://www.instagram.com/pnsg_1/'),
        'instagram_user'  => '@pnsg_1',
        'facebook_user'   => '/pnsgpitanga',
        'instagram_danca' => 'https://www.instagram.com/folclorekyivpitanga/',
    ],

    // Formulários externos (Google Forms) usados pela paróquia
    'formularios' => [
        'matricula_danca' => env(
            'FORM_MATRICULA_DANCA',
            'https://docs.google.com/forms/d/e/1FAIpQLSfpw4gImLav1fDcUC_e7IHnT4NNDeeXQDpRK7dGHtXqSPgPFg/viewform'
        ),
    ],

    'mapa' => [
        'embed' => 'https://www.google.com/maps?q=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR&output=embed',
        'rota'  => 'https://www.google.com/maps/dir/?api=1&destination=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR',
    ],

    // Conta administrativa criada pelo AdminSeeder
    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@paroquia.com'),
        'senha' => env('ADMIN_SENHA', 'trocar-esta-senha'),
    ],

];
