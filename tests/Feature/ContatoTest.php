<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pagina de contato.
 *
 * O site nao envia e-mail e nao tem formulario: a pagina apenas publica os
 * canais diretos da secretaria (WhatsApp, telefones, e-mail e endereco).
 */
class ContatoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pagina_de_contato_publica_os_canais_diretos()
    {
        $this->get(route('contato'))
            ->assertStatus(200)
            ->assertSee('wa.me', false)
            ->assertSee(config('paroquia.telefone'))
            ->assertSee(config('paroquia.celular'))
            ->assertSee(config('paroquia.email'))
            ->assertSee(config('paroquia.endereco.logradouro'));
    }

    /** @test */
    public function nao_existe_formulario_nem_rota_de_envio()
    {
        $this->assertFalse(
            \Illuminate\Support\Facades\Route::has('contato.enviar'),
            'O envio por e-mail foi removido do produto.'
        );

        $this->get(route('contato'))->assertDontSee('<form', false);
        $this->post('/contato', [])->assertStatus(405);
    }
}
