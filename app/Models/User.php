<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'inscricoes_grupo')
                    ->withTimestamps();
    }

    public function eventosVoluntario()
    {
        return $this->belongsToMany(Evento::class, 'voluntarios')
                    ->withPivot('mensagem')
                    ->withTimestamps();
    }
}
