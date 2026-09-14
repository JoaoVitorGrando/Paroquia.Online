<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * US016 - Sprint 3
 * Testes do fluxo de autenticação (cadastro, login, logout).
 */
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tela_de_cadastro_carrega_corretamente()
    {
        $response = $this->get(route('cadastro.form'));
        $response->assertStatus(200);
        $response->assertSee('Cadastr', false); // "Cadastrar" / "Cadastro"
    }

    /** @test */
    public function usuario_consegue_se_cadastrar_com_dados_validos()
    {
        $response = $this->post(route('cadastro.store'), [
            'name'                  => 'Maria Teste',
            'email'                 => 'maria@teste.com',
            'password'              => 'senha123',
            'password_confirmation' => 'senha123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'maria@teste.com']);
        $response->assertRedirect();
    }

    /** @test */
    public function cadastro_falha_quando_senhas_nao_conferem()
    {
        $response = $this->from(route('cadastro.form'))->post(route('cadastro.store'), [
            'name'                  => 'João Teste',
            'email'                 => 'joao@teste.com',
            'password'              => 'senha123',
            'password_confirmation' => 'outra-senha',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'joao@teste.com']);
    }

    /** @test */
    public function login_com_credenciais_validas_autentica_usuario()
    {
        User::create([
            'name'     => 'Pedro Login',
            'email'    => 'pedro@teste.com',
            'password' => Hash::make('senha123'),
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => 'pedro@teste.com',
            'password' => 'senha123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }

    /** @test */
    public function login_falha_com_senha_incorreta()
    {
        User::create([
            'name'     => 'Ana Login',
            'email'    => 'ana@teste.com',
            'password' => Hash::make('senha123'),
        ]);

        $response = $this->post(route('login.post'), [
            'email'    => 'ana@teste.com',
            'password' => 'errada',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('erro');
    }

    /** @test */
    public function logout_desautentica_usuario()
    {
        $user = User::create([
            'name'     => 'Carlos Logout',
            'email'    => 'carlos@teste.com',
            'password' => Hash::make('senha123'),
        ]);

        $this->actingAs($user)->post(route('logout'));
        $this->assertGuest();
    }
}
