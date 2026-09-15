<?php

namespace Tests\Feature;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * US016 - Sprint 3
 * Testes da vitrine pública de grupos e do gerenciamento pelo administrador.
 */
class GrupoControllerTest extends TestCase
{
    use RefreshDatabase;

    private function criarUsuario(bool $admin = false): User
    {
        return User::forceCreate([
            'name'     => $admin ? 'Admin Teste' : 'Usuário Teste',
            'email'    => $admin ? 'admin@teste.com' : 'user@teste.com',
            'password' => Hash::make('senha123'),
            'is_admin' => $admin,
        ]);
    }

    private function criarGrupo(): Grupo
    {
        return Grupo::create([
            'nome'        => 'Grupo de Jovens',
            'descricao'   => 'Encontros semanais para jovens da comunidade.',
            'responsavel' => 'Padre João',
            'dia_reuniao' => 'Sexta-feira',
            'ativo'       => true,
        ]);
    }

    /** @test */
    public function pagina_publica_de_grupos_lista_apenas_grupos_ativos()
    {
        $ativo = $this->criarGrupo();
        Grupo::create([
            'nome'      => 'Grupo Inativo',
            'descricao' => 'Não deve aparecer',
            'ativo'     => false,
        ]);

        $response = $this->get(route('grupos.index'));

        $response->assertStatus(200);
        $response->assertSee($ativo->nome);
        $response->assertDontSee('Grupo Inativo');
    }

    /** @test */
    public function visitante_anonimo_nao_pode_acessar_painel_admin_de_grupos()
    {
        $response = $this->get(route('admin.grupos'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function conta_sem_permissao_de_admin_nao_acessa_o_painel_de_grupos()
    {
        $user = $this->criarUsuario(false);

        $response = $this->actingAs($user)->get(route('admin.grupos'));
        $response->assertStatus(403);
    }

    /** @test */
    public function pagina_do_grupo_de_danca_traz_o_formulario_de_matricula()
    {
        Grupo::create([
            'nome'      => 'Grupo Folclórico Ucraniano Kyiv (Dança)',
            'descricao' => 'Danças folclóricas ucranianas.',
            'ativo'     => true,
        ]);

        $this->get(route('grupos.danca'))
            ->assertStatus(200)
            ->assertSee(config('paroquia.formularios.matricula_danca'), false)
            ->assertSee('Matrículas e rematrículas 2026');
    }

    /** @test */
    public function vitrine_de_grupos_oferece_contato_por_whatsapp()
    {
        $this->criarGrupo();

        $this->get(route('grupos.index'))
            ->assertStatus(200)
            ->assertSee('wa.me', false);
    }

    /** @test */
    public function admin_pode_criar_grupo_pelo_painel()
    {
        $admin = $this->criarUsuario(true);

        $this->actingAs($admin)->post(route('admin.grupos.salvar'), [
            'nome'      => 'Coral Paroquial',
            'descricao' => 'Cantos litúrgicos.',
        ]);

        $this->assertDatabaseHas('grupos', ['nome' => 'Coral Paroquial']);
    }

    /** @test */
    public function admin_pode_alternar_status_do_grupo()
    {
        $admin = $this->criarUsuario(true);
        $grupo = $this->criarGrupo();

        $this->actingAs($admin)->patch(route('admin.grupos.alternar', $grupo->id));

        $this->assertFalse((bool) $grupo->fresh()->ativo);
    }
}
