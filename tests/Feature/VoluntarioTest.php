<?php

namespace Tests\Feature;

use App\Models\Evento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CriaUsuarios;
use Tests\TestCase;

class VoluntarioTest extends TestCase
{
    use CriaUsuarios;
    use RefreshDatabase;

    private function criarEvento(): Evento
    {
        return Evento::create([
            'titulo'    => 'Quermesse',
            'descricao' => 'Evento com voluntários.',
            'data'      => now()->addDays(7)->toDateString(),
        ]);
    }

    /** @test */
    public function usuario_autenticado_inscreve_se_como_voluntario()
    {
        $user   = $this->criarUsuario();
        $evento = $this->criarEvento();

        $this->actingAs($user)->post(route('voluntario.inscrever', $evento->id), [
            'mensagem' => 'Posso ajudar na cozinha.',
        ]);

        $this->assertDatabaseHas('voluntarios', [
            'user_id'   => $user->id,
            'evento_id' => $evento->id,
        ]);
    }

    /** @test */
    public function usuario_nao_pode_se_inscrever_duas_vezes_como_voluntario()
    {
        $user   = $this->criarUsuario();
        $evento = $this->criarEvento();

        $this->actingAs($user)->post(route('voluntario.inscrever', $evento->id));
        $response = $this->actingAs($user)->post(route('voluntario.inscrever', $evento->id));

        $response->assertSessionHas('erro');
        $this->assertEquals(1, $user->fresh()->eventosVoluntario()->count());
    }

    /** @test */
    public function usuario_cancela_inscricao_de_voluntario()
    {
        $user   = $this->criarUsuario();
        $evento = $this->criarEvento();
        $user->eventosVoluntario()->attach($evento->id);

        $this->actingAs($user)->post(route('voluntario.cancelar', $evento->id));

        $this->assertDatabaseMissing('voluntarios', [
            'user_id'   => $user->id,
            'evento_id' => $evento->id,
        ]);
    }
}
