<?php

namespace Tests\Feature;

use App\Models\Aviso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

class AvisoTest extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    /** @test */
    public function admin_cria_aviso_com_destaque()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)->post(route('admin.avisos.salvar'), [
            'titulo'   => 'Campanha de Doação',
            'conteudo' => 'Participe da campanha.',
            'destaque' => '1',
        ])->assertRedirect(route('admin.avisos'));

        $this->assertDatabaseHas('avisos', [
            'titulo'   => 'Campanha de Doação',
            'destaque' => 1,
        ]);
    }

    /** @test */
    public function admin_exclui_aviso()
    {
        $admin = $this->criarAdmin();
        $aviso = Aviso::create([
            'titulo'   => 'Aviso Temporário',
            'conteudo' => 'Será removido.',
        ]);

        $this->actingAs($admin)->delete(route('admin.avisos.excluir', $aviso->id));

        $this->assertDatabaseMissing('avisos', ['id' => $aviso->id]);
    }

    /** @test */
    public function admin_acessa_listagem_de_avisos()
    {
        $admin = $this->criarAdmin();
        Aviso::create(['titulo' => 'Aviso Admin', 'conteudo' => 'Teste.']);

        $this->actingAs($admin)
            ->get(route('admin.avisos'))
            ->assertStatus(200)
            ->assertSee('Aviso Admin');
    }

    /** @test */
    public function cadastro_de_aviso_falha_sem_titulo()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)
            ->from(route('admin.avisos.criar'))
            ->post(route('admin.avisos.salvar'), ['conteudo' => 'Sem título'])
            ->assertSessionHasErrors('titulo');
    }

    /** @test */
    public function visitante_nao_acessa_admin_de_avisos()
    {
        $this->get(route('admin.avisos'))
            ->assertRedirect(route('login'));
    }
}
