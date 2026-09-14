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
    public function login_de_usuario_comum_redireciona_para_missas()
    {
        $this->criarUsuario(false, ['email' => 'fiel@paroquia.com']);

        $this->post(route('login.post'), [
            'email'    => 'fiel@paroquia.com',
            'password' => 'senha123',
        ])->assertRedirect(route('missas.index'));
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
    public function admin_visualiza_inscritos_do_grupo()
    {
        $admin = $this->criarAdmin();
        $user  = $this->criarUsuario(false, ['email' => 'inscrito@teste.com']);
        $grupo = Grupo::create([
            'nome'      => 'Coral',
            'descricao' => 'Grupo de canto.',
            'ativo'     => true,
        ]);
        $grupo->inscritos()->attach($user->id);

        $this->actingAs($admin)
            ->get(route('admin.grupos.inscritos', $grupo->id))
            ->assertStatus(200)
            ->assertSee('inscrito@teste.com');
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
