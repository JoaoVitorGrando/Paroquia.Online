<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CriaUsuarios
{
    protected function criarUsuario(bool $admin = false, array $attrs = []): User
    {
        return User::create(array_merge([
            'name'     => $admin ? 'Admin Teste' : 'Usuário Teste',
            'email'    => $admin ? 'admin@teste.com' : 'user@teste.com',
            'password' => Hash::make('senha123'),
            'is_admin' => $admin,
        ], $attrs));
    }

    protected function criarAdmin(array $attrs = []): User
    {
        return $this->criarUsuario(true, $attrs);
    }
}
