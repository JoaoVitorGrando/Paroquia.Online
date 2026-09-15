<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Missa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    /** @test */
    public function admin_acessa_painel_com_totais()
    {
        $admin = $this->criarAdmin();

        Evento::create(['titulo' => 'E1', 'descricao' => 'd', 'data' => now()->toDateString()]);
        Aviso::create(['titulo' => 'A1', 'conteudo' => 'c']);
        Grupo::create(['nome' => 'G1', 'descricao' => 'd', 'ativo' => true]);
        Missa::create(['dia_semana' => 'Domingo', 'horario' => '09:00', 'ativo' => true]);

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function login_de_admin_redireciona_para_painel()
    {
        $this->criarAdmin(['email' => 'adm@paroquia.com']);

        $this->post(route('login.post'), [
            'email'    => 'adm@paroquia.com',
            'password' => 'senha123',
        ])->assertRedirect(route('admin.index'));
    }

    /** @test */
    public function conta_sem_permissao_de_admin_nao_entra_no_painel()
    {
        $this->criarUsuario(false, ['email' => 'fiel@paroquia.com']);

        $this->post(route('login.post'), [
            'email'    => 'fiel@paroquia.com',
            'password' => 'senha123',
        ])->assertSessionHas('erro');

        $this->assertGuest();
    }

    /** @test */
    public function admin_edita_e_exclui_grupo()
    {
        $admin = $this->criarAdmin();
        $grupo = Grupo::create([
            'nome'      => 'Grupo Original',
            'descricao' => 'Descrição.',
            'ativo'     => true,
        ]);

        $this->actingAs($admin)->put(route('admin.grupos.atualizar', $grupo->id), [
            'nome'      => 'Grupo Atualizado',
            'descricao' => 'Nova descrição.',
        ]);

        $this->assertEquals('Grupo Atualizado', $grupo->fresh()->nome);

        $this->actingAs($admin)->delete(route('admin.grupos.excluir', $grupo->id));
        $this->assertDatabaseMissing('grupos', ['id' => $grupo->id]);
    }

    /** @test */
    public function o_painel_nao_expoe_mais_rotas_de_inscritos_ou_voluntarios()
    {
        foreach (['admin.grupos.inscritos', 'admin.eventos.voluntarios'] as $rota) {
            $this->assertFalse(
                \Illuminate\Support\Facades\Route::has($rota),
                "A rota {$rota} deveria ter sido removida junto com as inscricoes."
            );
        }
    }

    /** @test */
    public function todas_rotas_admin_bloqueiam_visitante()
    {
        $rotas = [
            'admin.index',
            'admin.eventos',
            'admin.avisos',
            'admin.grupos',
            'admin.missas',
        ];

        foreach ($rotas as $rota) {
            $this->get(route($rota))->assertRedirect(route('login'));
        }
    }
}
