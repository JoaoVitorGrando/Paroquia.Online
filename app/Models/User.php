<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * Campos preenchiveis em massa.
     *
     * 'is_admin' fica de fora de proposito: a flag de administrador nunca deve
     * poder ser definida a partir de dados de requisicao. Quem precisa marca-la
     * (o AdminSeeder) faz isso explicitamente com forceFill().
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
