<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

/**
 * Testes das defesas do sistema: privilegio de administrador, forca bruta
 * no login e abuso do formulario de contato.
 */
class SegurancaTest extends TestCase
{
    use RefreshDatabase, CriaUsuarios;

    /** @test */
    public function is_admin_nao_pode_ser_definido_por_atribuicao_em_massa()
    {
        $user = User::create([
            'name'     => 'Tentativa',
            'email'    => 'tentativa@teste.com',
            'password' => 'irrelevante',
            'is_admin' => true,
        ]);

        $this->assertFalse(
            (bool) $user->fresh()->is_admin,
            'is_admin nunca deve ser preenchido a partir de um array de dados externos.'
        );
    }

    /** @test */
    public function o_seeder_cria_o_administrador_com_a_flag_marcada()
    {
        $this->seed(AdminSeeder::class);

        $admin = User::where('email', config('paroquia.admin.email'))->first();

        $this->assertNotNull($admin);
        $this->assertTrue((bool) $admin->is_admin);
    }

    /** @test */
    public function login_bloqueia_apos_tentativas_seguidas()
    {
        $admin = $this->criarAdmin();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.post'), [
                'email'    => $admin->email,
                'password' => 'errada',
            ]);
        }

        $this->post(route('login.post'), [
            'email'    => $admin->email,
            'password' => 'errada',
        ])->assertStatus(429);
    }

    /** @test */
    public function toda_rota_administrativa_exige_login()
    {
        $rotas = [
            'admin.index', 'admin.eventos', 'admin.avisos', 'admin.grupos', 'admin.missas',
            'admin.eventos.criar', 'admin.avisos.criar', 'admin.grupos.criar', 'admin.missas.criar',
        ];

        foreach ($rotas as $rota) {
            $this->get(route($rota))->assertRedirect(route('login'));
        }
    }

    /** @test */
    public function conta_comum_autenticada_recebe_403_no_painel()
    {
        $user = $this->criarUsuario(false);

        foreach (['admin.index', 'admin.eventos', 'admin.grupos', 'admin.missas', 'admin.avisos'] as $rota) {
            $this->actingAs($user)->get(route($rota))->assertStatus(403);
        }
    }

    /** @test */
    public function a_senha_do_administrador_fica_guardada_como_hash()
    {
        $admin = $this->criarAdmin();

        $this->assertNotSame('senha123', $admin->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('senha123', $admin->password));
    }
}
