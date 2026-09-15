<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Em producao o APP_DEBUG fica desligado, entao qualquer endereco errado cai
 * nestas telas. Elas precisam abrir com o layout do site e com um caminho de
 * volta, e nao com a tela branca do framework.
 */
class PaginasDeErroTest extends TestCase
{
    use RefreshDatabase;

    public function test_endereco_inexistente_mostra_a_pagina_404_do_site(): void
    {
        $resposta = $this->get('/pagina-que-nao-existe');

        $resposta->assertStatus(404);
        $resposta->assertSee('Página não encontrada');
        $resposta->assertSee('Ir para a página inicial');
    }

    public function test_pagina_404_mantem_o_menu_de_navegacao(): void
    {
        $resposta = $this->get('/outro-endereco-invalido');

        $resposta->assertStatus(404);
        $resposta->assertSee(config('paroquia.nome_curto'));
    }
}
