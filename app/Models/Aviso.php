<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'destaque',
    ];

    protected $casts = [
        'destaque' => 'boolean',
    ];
}
