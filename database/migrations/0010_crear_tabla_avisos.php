<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnDelete();
            $table->foreignId('especie_id')->constrained('especies')->cascadeOnDelete();
            $table->date('fecha');
            $table->decimal('api10', 6, 2);
            $table->timestamps();

            $table->unique(['municipio_id', 'especie_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};