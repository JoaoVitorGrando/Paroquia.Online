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
        $admin = User::updateOrCreate(
            ['email' => config('paroquia.admin.email')],
            [
                'name'     => 'Administrador',
                'password' => Hash::make(config('paroquia.admin.senha')),
            ]
        );

        // is_admin nao e preenchivel em massa: marcada aqui, de forma explicita.
        $admin->forceFill(['is_admin' => true])->save();
    }
}
