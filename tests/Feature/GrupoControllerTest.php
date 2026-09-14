<?php

namespace Tests\Feature;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * US016 - Sprint 3
 * Testes de inscrição e gerenciamento de grupos.
 */
class GrupoControllerTest extends TestCase
{
    use RefreshDatabase;

    private function criarUsuario(bool $admin = false): User
    {
        return User::create([
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
    public function usuario_autenticado_consegue_se_inscrever_em_grupo()
    {
        $user  = $this->criarUsuario();
        $grupo = $this->criarGrupo();

        $this->actingAs($user)->post(route('grupos.inscrever', $grupo->id));

        $this->assertDatabaseHas('inscricoes_grupo', [
            'user_id'  => $user->id,
            'grupo_id' => $grupo->id,
        ]);
    }

    /** @test */
    public function usuario_nao_pode_se_inscrever_duas_vezes_no_mesmo_grupo()
    {
        $user  = $this->criarUsuario();
        $grupo = $this->criarGrupo();

        $this->actingAs($user)->post(route('grupos.inscrever', $grupo->id));
        $this->actingAs($user)->post(route('grupos.inscrever', $grupo->id));

        $this->assertEquals(1, $user->fresh()->grupos()->count());
    }

    /** @test */
    public function usuario_pode_cancelar_inscricao_em_grupo()
    {
        $user  = $this->criarUsuario();
        $grupo = $this->criarGrupo();
        $user->grupos()->attach($grupo->id);

        $this->actingAs($user)->post(route('grupos.cancelar', $grupo->id));

        $this->assertDatabaseMissing('inscricoes_grupo', [
            'user_id'  => $user->id,
            'grupo_id' => $grupo->id,
        ]);
    }

    /** @test */
    public function visitante_anonimo_nao_pode_acessar_painel_admin_de_grupos()
    {
        $response = $this->get(route('admin.grupos'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function usuario_comum_nao_pode_acessar_painel_admin_de_grupos()
    {
        $user = $this->criarUsuario(false);

        $response = $this->actingAs($user)->get(route('admin.grupos'));
        $response->assertStatus(403);
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
