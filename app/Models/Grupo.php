<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'responsavel',
        'dia_reuniao',
        'horario_reuniao',
        'local',
        'imagem',
        'ativo',
    ];

    public function inscritos()
    {
        return $this->belongsToMany(User::class, 'inscricoes_grupo')
                    ->withTimestamps();
    }
}
