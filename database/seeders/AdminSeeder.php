<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Credenciais fora do repositorio: definidas em ADMIN_EMAIL / ADMIN_SENHA no .env
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@paroquia.com')],
            [
                'name'     => 'Administrador',
                'password' => Hash::make(env('ADMIN_SENHA', 'trocar-esta-senha')),
                'is_admin' => true,
            ]
        );
    }
}
