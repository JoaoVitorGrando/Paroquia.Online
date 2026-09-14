<?php

namespace Tests\Unit;

use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Missa;
use PHPUnit\Framework\TestCase;

/**
 * US016 - Sprint 3
 * Testes unitários puros (sem banco) das classes de modelo.
 */
class ModelsTest extends TestCase
{
    /** @test */
    public function modelo_grupo_tem_campos_fillable_esperados()
    {
        $grupo = new Grupo();
        $esperados = ['nome', 'descricao', 'responsavel', 'dia_reuniao', 'horario_reuniao', 'local', 'ativo'];

        foreach ($esperados as $campo) {
            $this->assertContains($campo, $grupo->getFillable(),
                "Campo {$campo} deveria estar em fillable do Grupo.");
        }
    }

    /** @test */
    public function modelo_missa_tem_cast_de_ativo_para_boolean()
    {
        $missa = new Missa();
        $this->assertArrayHasKey('ativo', $missa->getCasts());
        $this->assertSame('boolean', $missa->getCasts()['ativo']);
    }

    /** @test */
    public function modelo_evento_e_instanciavel()
    {
        $this->assertInstanceOf(Evento::class, new Evento());
    }
}
