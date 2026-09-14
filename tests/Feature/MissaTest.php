<?php

namespace Tests\Feature;

use App\Models\Missa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

class MissaTest extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    /** @test */
    public function listar_ordenadas_retorna_missas_por_dia_e_horario()
    {
        Missa::create(['dia_semana' => 'Sábado', 'horario' => '18:00', 'ativo' => true]);
        Missa::create(['dia_semana' => 'Domingo', 'horario' => '09:00', 'ativo' => true]);
        Missa::create(['dia_semana' => 'Domingo', 'horario' => '11:00', 'ativo' => true]);

        $missas = Missa::listarOrdenadas();

        $this->assertSame('Domingo', $missas[0]->dia_semana);
        $this->assertSame('09:00', $missas[0]->horario);
        $this->assertSame('Domingo', $missas[1]->dia_semana);
        $this->assertSame('Sábado', $missas[2]->dia_semana);
    }

    /** @test */
    public function admin_acessa_gerenciamento_de_missas()
    {
        Missa::create([
            'dia_semana' => 'Domingo',
            'horario'    => '08:00',
            'local'      => 'Matriz',
            'ativo'      => true,
        ]);

        $admin = $this->criarAdmin();

        $this->actingAs($admin)
            ->get(route('admin.missas'))
            ->assertStatus(200)
            ->assertSee('08:00');
    }

    /** @test */
    public function admin_cria_horario_de_missa()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)->post(route('admin.missas.salvar'), [
            'dia_semana' => 'Domingo',
            'horario'    => '11:00',
            'local'      => 'Igreja Matriz',
            'observacao' => 'Missa em latim',
        ])->assertRedirect(route('admin.missas'));

        $this->assertDatabaseHas('missas', [
            'dia_semana' => 'Domingo',
            'horario'    => '11:00',
            'local'      => 'Igreja Matriz',
        ]);
    }

    /** @test */
    public function admin_edita_horario_de_missa()
    {
        $admin = $this->criarAdmin();
        $missa = Missa::create([
            'dia_semana' => 'Sexta-feira',
            'horario'    => '19:00',
            'ativo'      => true,
        ]);

        $this->actingAs($admin)->put(route('admin.missas.atualizar', $missa->id), [
            'dia_semana' => 'Sexta-feira',
            'horario'    => '20:00',
            'local'      => 'Capela',
        ]);

        $this->assertEquals('20:00', $missa->fresh()->horario);
        $this->assertEquals('Capela', $missa->fresh()->local);
    }

    /** @test */
    public function admin_alterna_status_da_missa()
    {
        $admin = $this->criarAdmin();
        $missa = Missa::create([
            'dia_semana' => 'Domingo',
            'horario'    => '07:00',
            'ativo'      => true,
        ]);

        $this->actingAs($admin)->patch(route('admin.missas.alternar', $missa->id));

        $this->assertFalse($missa->fresh()->ativo);
    }

    /** @test */
    public function admin_exclui_horario_de_missa()
    {
        $admin = $this->criarAdmin();
        $missa = Missa::create([
            'dia_semana' => 'Quarta-feira',
            'horario'    => '18:30',
            'ativo'      => true,
        ]);

        $this->actingAs($admin)->delete(route('admin.missas.excluir', $missa->id));

        $this->assertDatabaseMissing('missas', ['id' => $missa->id]);
    }

    /** @test */
    public function cadastro_de_missa_falha_com_dados_invalidos()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)
            ->from(route('admin.missas.criar'))
            ->post(route('admin.missas.salvar'), [
                'dia_semana' => 'Dia inválido',
                'horario'    => '25:99',
            ])
            ->assertSessionHasErrors(['dia_semana', 'horario']);
    }

    /** @test */
    public function usuario_comum_nao_acessa_admin_de_missas()
    {
        $user = $this->criarUsuario();

        $this->actingAs($user)
            ->get(route('admin.missas'))
            ->assertStatus(403);
    }
}
