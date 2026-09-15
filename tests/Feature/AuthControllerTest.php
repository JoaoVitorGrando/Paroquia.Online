<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

/**
 * Acesso administrativo.
 *
 * O site e informativo e nao possui cadastro publico: o unico login existente
 * e o do administrador da paroquia.
 */
class AuthControllerTest extends TestCase
{
    use RefreshDatabase, CriaUsuarios;

    /** @test */
    public function nao_existe_rota_de_cadastro_publico()
    {
        $this->assertFalse(
            \Illuminate\Support\Facades\Route::has('cadastro.form'),
            'O cadastro publico foi removido do produto.'
        );

        $this->get('/cadastro')->assertNotFound();
    }

    /** @test */
    public function tela_de_login_carrega_como_area_administrativa()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Acesso administrativo');
    }

    /** @test */
    public function administrador_entra_e_cai_no_painel()
    {
        $admin = $this->criarAdmin();

        $this->post(route('login.post'), [
            'email'    => $admin->email,
            'password' => 'senha123',
        ])->assertRedirect(route('admin.index'));

        $this->assertAuthenticated();
    }

    /** @test */
    public function login_falha_com_senha_incorreta()
    {
        $admin = $this->criarAdmin();

        $this->post(route('login.post'), [
            'email'    => $admin->email,
            'password' => 'errada',
        ])->assertSessionHas('erro');

        $this->assertGuest();
    }

    /** @test */
    public function conta_sem_permissao_de_admin_e_recusada()
    {
        $comum = $this->criarUsuario(false);

        $this->post(route('login.post'), [
            'email'    => $comum->email,
            'password' => 'senha123',
        ])->assertSessionHas('erro');

        $this->assertGuest();
    }

    /** @test */
    public function logout_encerra_a_sessao_e_volta_para_a_home()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
