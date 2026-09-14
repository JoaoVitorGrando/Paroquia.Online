<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            MissaSeeder::class,
            EventoSeeder::class,
            GrupoSeeder::class,
            AvisoSeeder::class,
        ]);
    }
}
