<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Evento;
use App\Models\Missa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

class HomeAndPagesTest extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    /** @test */
    public function home_exibe_conteudo_da_paroquia()
    {
        Evento::create([
            'titulo'    => 'Festa Paroquial',
            'descricao' => 'Evento de teste.',
            'data'      => now()->addDays(3)->toDateString(),
        ]);

        Missa::create([
            'dia_semana' => 'Domingo',
            'horario'    => '09:00',
            'local'      => 'Igreja Matriz',
            'ativo'      => true,
        ]);

        Aviso::create([
            'titulo'   => 'Aviso Importante',
            'conteudo' => 'Conteúdo do aviso.',
            'destaque' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Paróquia Nossa Senhora da Glória');
        $response->assertSee('Festa Paroquial');
        $response->assertSee('Aviso Importante');
        $response->assertSee('09:00');
    }

    /** @test */
    public function pagina_sobre_carrega()
    {
        $this->get(route('sobre'))
            ->assertStatus(200)
            ->assertSee('Paróquia', false);
    }

    /** @test */
    public function pagina_de_missas_lista_apenas_missas_ativas()
    {
        Missa::create([
            'dia_semana' => 'Domingo',
            'horario'    => '10:00',
            'local'      => 'Matriz',
            'ativo'      => true,
        ]);

        Missa::create([
            'dia_semana' => 'Sábado',
            'horario'    => '18:00',
            'local'      => 'Capela',
            'ativo'      => false,
        ]);

        $response = $this->get(route('missas.index'));

        $response->assertStatus(200);
        $response->assertSee('10:00');
        $response->assertDontSee('18:00');
    }

    /** @test */
    public function pagina_de_avisos_lista_avisos_cadastrados()
    {
        Aviso::create([
            'titulo'   => 'Reunião de Pastoral',
            'conteudo' => 'Domingo após a missa.',
        ]);

        $this->get(route('avisos.index'))
            ->assertStatus(200)
            ->assertSee('Reunião de Pastoral');
    }

    /** @test */
    public function visitante_nao_autenticado_e_redirecionado_ao_tentar_inscrever_em_grupo()
    {
        $this->post(route('grupos.inscrever', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function visitante_nao_autenticado_e_redirecionado_ao_tentar_voluntariado()
    {
        $this->post(route('voluntario.inscrever', 1))
            ->assertRedirect(route('login'));
    }
}
