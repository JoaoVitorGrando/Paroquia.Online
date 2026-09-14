<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'data',
        'horario',
        'local',
        'imagem',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    // US008 - Voluntarios inscritos no evento
    public function voluntarios()
    {
        return $this->belongsToMany(User::class, 'voluntarios')
                    ->withPivot('mensagem')
                    ->withTimestamps();
    }
}
