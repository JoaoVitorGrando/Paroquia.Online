<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voluntarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->string('mensagem')->nullable();
            $table->timestamp('inscrito_em')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'evento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voluntarios');
    }
};
