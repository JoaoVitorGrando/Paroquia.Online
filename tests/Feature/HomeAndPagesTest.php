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
    public function o_site_nao_expoe_area_de_cadastro_ou_login_no_menu()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertDontSee('Cadastre-se')
            ->assertDontSee(route('login'), false);
    }

    /** @test */
    public function home_esconde_a_secao_de_eventos_quando_nao_ha_nenhum()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertDontSee('Próximos eventos')
            ->assertDontSee('Nenhum evento');
    }

    /** @test */
    public function home_esconde_as_secoes_de_avisos_e_grupos_quando_vazias()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertDontSee('Avisos em destaque')
            ->assertDontSee('Nossos grupos e pastorais');
    }

    /** @test */
    public function paginas_de_listagem_usam_estado_vazio_discreto_sem_alerta()
    {
        foreach (['eventos.index', 'grupos.index', 'avisos.index', 'missas.index'] as $rota) {
            $this->get(route($rota))
                ->assertStatus(200)
                ->assertDontSee('alert-info', false)
                ->assertSee('estado-vazio', false);
        }
    }

    /** @test */
    public function o_rodape_traz_os_dados_institucionais_da_paroquia()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertSee(config('paroquia.cnpj'))
            ->assertSee(config('paroquia.telefone'))
            ->assertSee(config('paroquia.email'))
            ->assertSee(config('paroquia.redes.facebook'), false)
            ->assertSee(config('paroquia.redes.instagram'), false);
    }

    /** @test */
    public function a_pagina_de_contato_mostra_endereco_telefones_e_redes()
    {
        $this->get(route('contato'))
            ->assertStatus(200)
            ->assertSee(config('paroquia.endereco.logradouro'))
            ->assertSee(config('paroquia.endereco.numero'))
            ->assertSee(config('paroquia.endereco.cep'))
            ->assertSee(config('paroquia.celular'))
            ->assertSee(config('paroquia.redes.facebook'), false);
    }

    /** @test */
    public function toda_pagina_publica_oferece_contato_por_whatsapp()
    {
        foreach (['home', 'missas.index', 'eventos.index', 'grupos.index', 'avisos.index', 'contato'] as $rota) {
            $this->get(route($rota))
                ->assertStatus(200)
                ->assertSee('wa.me', false);
        }
    }
}
