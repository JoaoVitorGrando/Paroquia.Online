<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscricoes_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->timestamp('inscrito_em')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'grupo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricoes_grupo');
    }
};
