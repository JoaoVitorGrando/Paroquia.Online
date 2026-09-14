<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * US016 - Sprint 3
 * Testes de eventos (listagem pública e CRUD admin).
 */
class EventoControllerTest extends TestCase
{
    use RefreshDatabase;

    private function criarAdmin(): User
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'admin@teste.com',
            'password' => Hash::make('senha123'),
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function pagina_publica_de_eventos_lista_eventos_cadastrados()
    {
        Evento::create([
            'titulo'    => 'Festa de São João',
            'descricao' => 'Festa junina paroquial.',
            'data'      => now()->addDays(5)->toDateString(),
            'local'     => 'Salão da Igreja',
        ]);

        $response = $this->get(route('eventos.index'));

        $response->assertStatus(200);
        $response->assertSee('Festa de São João');
    }

    /** @test */
    public function admin_consegue_cadastrar_evento_com_dados_validos()
    {
        $admin = $this->criarAdmin();

        $response = $this->actingAs($admin)->post(route('admin.eventos.salvar'), [
            'titulo'    => 'Quermesse',
            'descricao' => 'Quermesse anual.',
            'data'      => now()->addDays(10)->toDateString(),
            'local'     => 'Pátio',
        ]);

        $this->assertDatabaseHas('eventos', ['titulo' => 'Quermesse']);
        $response->assertRedirect(route('admin.eventos'));
    }

    /** @test */
    public function cadastro_de_evento_falha_sem_titulo()
    {
        $admin = $this->criarAdmin();

        $response = $this->actingAs($admin)
            ->from(route('admin.eventos.criar'))
            ->post(route('admin.eventos.salvar'), [
                'descricao' => 'Sem título',
                'data'      => now()->addDay()->toDateString(),
            ]);

        $response->assertSessionHasErrors('titulo');
        $this->assertDatabaseMissing('eventos', ['descricao' => 'Sem título']);
    }

    /** @test */
    public function admin_consegue_editar_evento_existente()
    {
        $admin  = $this->criarAdmin();
        $evento = Evento::create([
            'titulo'    => 'Antigo',
            'descricao' => 'Descrição antiga.',
            'data'      => now()->addDays(5)->toDateString(),
        ]);

        $this->actingAs($admin)->put(route('admin.eventos.atualizar', $evento->id), [
            'titulo'    => 'Atualizado',
            'descricao' => 'Nova descrição.',
            'data'      => $evento->data,
        ]);

        $this->assertEquals('Atualizado', $evento->fresh()->titulo);
    }

    /** @test */
    public function admin_consegue_excluir_evento()
    {
        $admin  = $this->criarAdmin();
        $evento = Evento::create([
            'titulo'    => 'Para excluir',
            'descricao' => '...',
            'data'      => now()->addDay()->toDateString(),
        ]);

        $this->actingAs($admin)->delete(route('admin.eventos.excluir', $evento->id));

        $this->assertDatabaseMissing('eventos', ['id' => $evento->id]);
    }

    /** @test */
    public function visitante_nao_pode_acessar_admin_de_eventos()
    {
        $response = $this->get(route('admin.eventos'));
        $response->assertRedirect(route('login'));
    }
}
