<?php

namespace Tests\Feature;

use App\Mail\ContatoRecebido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * US015 + US016 - Sprint 3
 * Testa o envio real de e-mail pelo formulário de contato.
 */
class ContatoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pagina_de_contato_carrega()
    {
        $this->get(route('contato'))->assertStatus(200);
    }

    /** @test */
    public function formulario_dispara_envio_de_email_com_dados_validos()
    {
        Mail::fake();

        $this->post(route('contato.enviar'), [
            'nome'     => 'João Fiel',
            'email'    => 'joao@fiel.com',
            'assunto'  => 'Pedido de oração',
            'mensagem' => 'Por favor, rezem pela minha família.',
        ])->assertRedirect();

        Mail::assertSent(ContatoRecebido::class, function ($mail) {
            return $mail->emailRemetente === 'joao@fiel.com'
                && $mail->assunto === 'Pedido de oração';
        });
    }

    /** @test */
    public function formulario_falha_sem_campos_obrigatorios()
    {
        Mail::fake();

        $this->from(route('contato'))
            ->post(route('contato.enviar'), [])
            ->assertSessionHasErrors(['nome', 'email', 'assunto', 'mensagem']);

        Mail::assertNothingSent();
    }
}
