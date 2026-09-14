<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Missa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

/**
 * Sprint 4 - correções e funcionalidades novas.
 */
class Sprint4Test extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    // ---------- Ordenação dos horários de missa ----------

    /** @test */
    public function indice_dia_ignora_hifen_e_acento()
    {
        $this->assertSame(5, Missa::indiceDia('Sexta feira'));
        $this->assertSame(5, Missa::indiceDia('Sexta-feira'));
        $this->assertSame(6, Missa::indiceDia('Sábado'));
        $this->assertSame(6, Missa::indiceDia('sabado'));
        $this->assertSame(2, Missa::indiceDia('Terça-feira'));
        $this->assertSame(0, Missa::indiceDia('Domingo'));
        $this->assertNull(Missa::indiceDia('Dia inválido'));
        $this->assertNull(Missa::indiceDia(null));
    }

    /** @test */
    public function listar_ordenadas_aceita_dias_sem_hifen()
    {
        Missa::create(['dia_semana' => 'Sábado', 'horario' => '18:00', 'ativo' => true]);
        Missa::create(['dia_semana' => 'Sexta feira', 'horario' => '19:00', 'ativo' => true]);
        Missa::create(['dia_semana' => 'Domingo', 'horario' => '09:00', 'ativo' => true]);

        $missas = Missa::listarOrdenadas();

        $this->assertSame('Domingo', $missas[0]->dia_semana);
        $this->assertSame('Sexta feira', $missas[1]->dia_semana);
        $this->assertSame('Sábado', $missas[2]->dia_semana);
    }

    /** @test */
    public function proxima_missa_retorna_nulo_sem_missas_cadastradas()
    {
        $this->assertNull(Missa::proxima());

        // A home e a página de missas não podem quebrar com o banco vazio
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('missas.index'))->assertStatus(200);
    }

    // ---------- Páginas novas ----------

    /** @test */
    public function paginas_de_catequese_e_sacramentos_carregam()
    {
        $this->get(route('catequese'))->assertStatus(200)->assertSee('Catequese');
        $this->get(route('sacramentos'))->assertStatus(200)->assertSee('Batizado');
    }

    /** @test */
    public function paginas_publicas_exibem_link_de_whatsapp_da_secretaria()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertSee('wa.me/' . config('paroquia.whatsapp'), false);
    }

    // ---------- Editar aviso ----------

    /** @test */
    public function admin_edita_aviso()
    {
        $admin = $this->criarAdmin();
        $aviso = Aviso::create(['titulo' => 'Antigo', 'conteudo' => 'Texto antigo', 'destaque' => false]);

        $this->actingAs($admin)
            ->get(route('admin.avisos.editar', $aviso->id))
            ->assertStatus(200)
            ->assertSee('Antigo');

        $this->actingAs($admin)->put(route('admin.avisos.atualizar', $aviso->id), [
            'titulo'   => 'Novo título',
            'conteudo' => 'Texto novo',
            'destaque' => 'on',
        ])
            ->assertRedirect(route('admin.avisos'))
            ->assertSessionHas('sucesso', 'Aviso atualizado com sucesso!');

        $aviso->refresh();
        $this->assertSame('Novo título', $aviso->titulo);
        $this->assertTrue($aviso->destaque);
    }

    /** @test */
    public function editar_aviso_falha_sem_titulo()
    {
        $admin = $this->criarAdmin();
        $aviso = Aviso::create(['titulo' => 'A', 'conteudo' => 'B']);

        $this->actingAs($admin)
            ->from(route('admin.avisos.editar', $aviso->id))
            ->put(route('admin.avisos.atualizar', $aviso->id), ['titulo' => '', 'conteudo' => ''])
            ->assertSessionHasErrors(['titulo', 'conteudo']);
    }

    // ---------- Voluntários de um evento ----------

    /** @test */
    public function admin_visualiza_voluntarios_do_evento()
    {
        $admin  = $this->criarAdmin();
        $user   = $this->criarUsuario(false, ['email' => 'voluntario@teste.com']);
        $evento = Evento::create([
            'titulo'    => 'Festa Junina',
            'descricao' => 'Festa da comunidade.',
            'data'      => now()->addDays(5)->toDateString(),
        ]);

        $evento->voluntarios()->attach($user->id, ['mensagem' => 'Posso ajudar na cozinha']);

        $this->actingAs($admin)
            ->get(route('admin.eventos.voluntarios', $evento->id))
            ->assertStatus(200)
            ->assertSee('voluntario@teste.com')
            ->assertSee('Posso ajudar na cozinha');

        // Contador clicável na listagem de eventos
        $this->actingAs($admin)
            ->get(route('admin.eventos'))
            ->assertStatus(200)
            ->assertSee(route('admin.eventos.voluntarios', $evento->id), false);
    }

    // ---------- Administrador não se inscreve ----------

    /** @test */
    public function administrador_nao_se_inscreve_em_grupo()
    {
        $admin = $this->criarAdmin();
        $grupo = Grupo::create(['nome' => 'Coral', 'descricao' => 'Canto.', 'ativo' => true]);

        $this->actingAs($admin)
            ->from(route('grupos.index'))
            ->post(route('grupos.inscrever', $grupo->id))
            ->assertSessionHas('erro');

        $this->assertDatabaseCount('inscricoes_grupo', 0);
    }

    /** @test */
    public function administrador_nao_se_candidata_a_voluntario()
    {
        $admin  = $this->criarAdmin();
        $evento = Evento::create([
            'titulo'    => 'Quermesse',
            'descricao' => 'Evento.',
            'data'      => now()->addDay()->toDateString(),
        ]);

        $this->actingAs($admin)
            ->from(route('eventos.index'))
            ->post(route('voluntario.inscrever', $evento->id))
            ->assertSessionHas('erro');

        $this->assertDatabaseCount('voluntarios', 0);
    }

    // ---------- Fotos em grupos e eventos ----------

    /** @test */
    public function grupo_pode_ser_criado_com_e_sem_foto()
    {
        $admin = $this->criarAdmin();

        // Sem foto
        $this->actingAs($admin)->post(route('admin.grupos.salvar'), [
            'nome'      => 'Grupo sem foto',
            'descricao' => 'Descrição.',
        ])->assertRedirect(route('admin.grupos'));

        $this->assertNull(Grupo::where('nome', 'Grupo sem foto')->first()->imagem);

        // Com foto
        $this->actingAs($admin)->post(route('admin.grupos.salvar'), [
            'nome'      => 'Grupo com foto',
            'descricao' => 'Descrição.',
            'imagem'    => UploadedFile::fake()->image('foto.jpg', 40, 40),
        ])->assertRedirect(route('admin.grupos'));

        $grupo = Grupo::where('nome', 'Grupo com foto')->first();
        $this->assertNotNull($grupo->imagem);
        $this->assertStringStartsWith('uploads/grupos/', $grupo->imagem);
        $this->assertFileExists(public_path($grupo->imagem));

        // Remover a foto apaga o arquivo do disco
        $caminho = public_path($grupo->imagem);

        $this->actingAs($admin)->put(route('admin.grupos.atualizar', $grupo->id), [
            'nome'           => 'Grupo com foto',
            'descricao'      => 'Descrição.',
            'remover_imagem' => 'on',
        ]);

        $this->assertNull($grupo->fresh()->imagem);
        $this->assertFileDoesNotExist($caminho);
    }

    /** @test */
    public function excluir_evento_apaga_a_foto_do_disco()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)->post(route('admin.eventos.salvar'), [
            'titulo'    => 'Evento com foto',
            'descricao' => 'Descrição.',
            'data'      => now()->addDays(2)->toDateString(),
            'imagem'    => UploadedFile::fake()->image('evento.png', 40, 40),
        ])->assertRedirect(route('admin.eventos'));

        $evento  = Evento::where('titulo', 'Evento com foto')->first();
        $caminho = public_path($evento->imagem);
        $this->assertFileExists($caminho);

        $this->actingAs($admin)->delete(route('admin.eventos.excluir', $evento->id));

        $this->assertFileDoesNotExist($caminho);
        $this->assertDatabaseMissing('eventos', ['id' => $evento->id]);
    }

    /** @test */
    public function upload_recusa_arquivo_que_nao_e_imagem()
    {
        $admin = $this->criarAdmin();

        $this->actingAs($admin)
            ->from(route('admin.grupos.criar'))
            ->post(route('admin.grupos.salvar'), [
                'nome'      => 'Grupo inválido',
                'descricao' => 'Descrição.',
                'imagem'    => UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('imagem');
    }

    // ---------- Dashboard ----------

    /** @test */
    public function dashboard_mostra_indicadores_secundarios()
    {
        $admin = $this->criarAdmin();

        Evento::create(['titulo' => 'Futuro', 'descricao' => 'd', 'data' => now()->addDays(3)->toDateString()]);
        Aviso::create(['titulo' => 'Destaque', 'conteudo' => 'c', 'destaque' => true]);
        Missa::create(['dia_semana' => 'Domingo', 'horario' => '09:00', 'ativo' => true]);
        Grupo::create(['nome' => 'Coral', 'descricao' => 'd', 'ativo' => true]);

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertStatus(200)
            ->assertSee('Painel administrativo')
            ->assertSee('próximos')
            ->assertSee('em destaque')
            ->assertSee('ativas')
            ->assertSee('inscritos');
    }
}
