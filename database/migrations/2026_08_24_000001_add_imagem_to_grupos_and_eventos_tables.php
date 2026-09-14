<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Fotos em grupos e eventos (pedido da paróquia)
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->string('imagem')->nullable()->after('local');
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->string('imagem')->nullable()->after('local');
        });
    }

    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn('imagem');
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('imagem');
        });
    }
};
