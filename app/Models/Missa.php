<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Missa extends Model
{
    use HasFactory;

    protected $fillable = [
        'dia_semana',
        'horario',
        'local',
        'observacao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // US001 - Converte o nome do dia no indice do Carbon (0 = Domingo ... 6 = Sabado).
    // Tolerante: usa so a primeira palavra e ignora acentos ("Sexta feira" = "Sexta-feira").
    public static function indiceDia(?string $dia): ?int
    {
        if ($dia === null || trim($dia) === '') {
            return null;
        }

        $mapa = [
            'domingo' => 0,
            'segunda' => 1,
            'terca'   => 2,
            'quarta'  => 3,
            'quinta'  => 4,
            'sexta'   => 5,
            'sabado'  => 6,
        ];

        // Primeira palavra: "Sexta feira" / "Sexta-feira" -> "Sexta"
        $partes   = preg_split('/[\s\-]+/u', trim($dia));
        $primeira = $partes[0] ?? '';

        // Minusculas + remocao de acentos
        $chave = mb_strtolower($primeira, 'UTF-8');
        $chave = strtr($chave, [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'ê' => 'e', 'è' => 'e', 'ë' => 'e',
            'í' => 'i', 'î' => 'i', 'ì' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ò' => 'o', 'ö' => 'o',
            'ú' => 'u', 'û' => 'u', 'ù' => 'u', 'ü' => 'u',
            'ç' => 'c',
        ]);

        return $mapa[$chave] ?? null;
    }

    // US001 - Ordena por dia da semana e, dentro do mesmo dia, por horario
    public static function listarOrdenadas()
    {
        return static::query()
            ->get()
            ->sortBy(function ($missa) {
                $indice = static::indiceDia($missa->dia_semana);

                return sprintf('%02d', $indice ?? 99) . ' ' . (string) $missa->horario;
            })
            ->values();
    }

    // US001 - Proxima missa a ser celebrada (faixa de destaque da home e da pagina de missas)
    public static function proxima(): ?array
    {
        $missas = static::where('ativo', true)->get();

        if ($missas->isEmpty()) {
            return null;
        }

        $agora     = now();
        $hojeIdx   = (int) $agora->dayOfWeek;
        $agoraHhmm = (int) $agora->format('Hi');

        $melhor    = null;
        $menorPeso = null;

        foreach ($missas as $missa) {
            $indice = static::indiceDia($missa->dia_semana);

            if ($indice === null) {
                continue;
            }

            $horario = substr((string) $missa->horario, 0, 5);
            $hhmm    = (int) str_replace(':', '', $horario);

            $faltam = ($indice - $hojeIdx + 7) % 7;

            // Cai hoje mas o horario ja passou: vale so na semana seguinte
            if ($faltam === 0 && $hhmm <= $agoraHhmm) {
                $faltam = 7;
            }

            $peso = $faltam * 10000 + $hhmm;

            if ($menorPeso === null || $peso < $menorPeso) {
                $menorPeso = $peso;
                $melhor    = [
                    'missa'   => $missa,
                    'horario' => $horario,
                    'quando'  => $faltam === 0 ? 'Hoje' : ($faltam === 1 ? 'Amanhã' : $missa->dia_semana),
                ];
            }
        }

        return $melhor;
    }
}
