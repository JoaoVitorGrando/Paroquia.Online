<?php

namespace Tests\Feature;

use App\Mail\ContatoRecebido;
use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Missa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Testes end-to-end percorrendo os fluxos principais do sistema.
 */
class EndToEndFluxoCompletoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function fluxo_visitante_navega_pelo_site_e_envia_contato()
    {
        Mail::fake();

        Evento::create([
            'titulo'    => 'Festa Junina',
            'descricao' => 'Festa da comunidade.',
            'data'      => now()->addDays(5)->toDateString(),
        ]);

        Missa::create([
            'dia_semana' => 'Domingo',
            'horario'    => '09:00',
            'local'      => 'Matriz',
            'ativo'      => true,
        ]);

        Grupo::create([
            'nome'      => 'Grupo de Jovens',
            'descricao' => 'Encontros semanais.',
            'ativo'     => true,
        ]);

        $this->get(route('home'))->assertOk();
        $this->get(route('sobre'))->assertOk();
        $this->get(route('missas.index'))->assertOk()->assertSee('09:00');
        $this->get(route('eventos.index'))->assertOk()->assertSee('Festa Junina');
        $this->get(route('grupos.index'))->assertOk()->assertSee('Grupo de Jovens');
        $this->get(route('avisos.index'))->assertOk();
        $this->get(route('contato'))->assertOk();

        $this->post(route('contato.enviar'), [
            'nome'     => 'Maria Silva',
            'email'    => 'maria@email.com',
            'assunto'  => 'Informações',
            'mensagem' => 'Gostaria de saber mais sobre os grupos.',
        ])->assertRedirect();

        Mail::assertSent(ContatoRecebido::class);
    }

    /** @test */
    public function fluxo_usuario_cadastra_login_inscreve_e_voluntaria()
    {
        $evento = Evento::create([
            'titulo'    => 'Retiro',
            'descricao' => 'Retiro espiritual.',
            'data'      => now()->addDays(10)->toDateString(),
        ]);

        $grupo = Grupo::create([
            'nome'      => 'Apostolado',
            'descricao' => 'Grupo de oração.',
            'ativo'     => true,
        ]);

        $this->post(route('cadastro.store'), [
            'name'                  => 'João Fiel',
            'email'                 => 'joao@fiel.com',
            'password'              => 'senha123',
            'password_confirmation' => 'senha123',
        ])->assertRedirect(route('login'));

        $this->post(route('login.post'), [
            'email'    => 'joao@fiel.com',
            'password' => 'senha123',
        ])->assertRedirect(route('missas.index'));

        $this->post(route('grupos.inscrever', $grupo->id));
        $this->assertDatabaseHas('inscricoes_grupo', [
            'grupo_id' => $grupo->id,
        ]);

        $this->post(route('voluntario.inscrever', $evento->id), [
            'mensagem' => 'Posso ajudar.',
        ]);
        $this->assertDatabaseHas('voluntarios', [
            'evento_id' => $evento->id,
        ]);

        $this->post(route('grupos.cancelar', $grupo->id));
        $this->assertDatabaseMissing('inscricoes_grupo', ['grupo_id' => $grupo->id]);

        $this->post(route('voluntario.cancelar', $evento->id));
        $this->assertDatabaseMissing('voluntarios', ['evento_id' => $evento->id]);

        $this->post(route('logout'));
        $this->assertGuest();
    }

    /** @test */
    public function fluxo_admin_gerencia_todo_o_conteudo()
    {
        $this->post(route('cadastro.store'), [
            'name'                  => 'Admin Sistema',
            'email'                 => 'admin@fluxo.com',
            'password'              => 'senha123',
            'password_confirmation' => 'senha123',
        ]);

        \App\Models\User::where('email', 'admin@fluxo.com')->update(['is_admin' => true]);

        $this->post(route('login.post'), [
            'email'    => 'admin@fluxo.com',
            'password' => 'senha123',
        ])->assertRedirect(route('admin.index'));

        $this->post(route('admin.eventos.salvar'), [
            'titulo'    => 'Evento Admin',
            'descricao' => 'Criado no teste E2E.',
            'data'      => now()->addDays(3)->toDateString(),
        ])->assertRedirect(route('admin.eventos'));

        $this->post(route('admin.avisos.salvar'), [
            'titulo'   => 'Aviso Admin',
            'conteudo' => 'Publicado no teste E2E.',
        ])->assertRedirect(route('admin.avisos'));

        $this->post(route('admin.grupos.salvar'), [
            'nome'      => 'Grupo Admin',
            'descricao' => 'Criado no teste E2E.',
        ])->assertRedirect(route('admin.grupos'));

        $this->post(route('admin.missas.salvar'), [
            'dia_semana' => 'Domingo',
            'horario'    => '08:30',
            'local'      => 'Matriz',
        ])->assertRedirect(route('admin.missas'));

        $this->assertDatabaseHas('eventos', ['titulo' => 'Evento Admin']);
        $this->assertDatabaseHas('avisos', ['titulo' => 'Aviso Admin']);
        $this->assertDatabaseHas('grupos', ['nome' => 'Grupo Admin']);
        $this->assertDatabaseHas('missas', ['horario' => '08:30']);

        $this->get(route('admin.eventos'))->assertOk();
        $this->get(route('admin.avisos'))->assertOk();
        $this->get(route('admin.grupos'))->assertOk();
        $this->get(route('admin.missas'))->assertOk();
    }
}
