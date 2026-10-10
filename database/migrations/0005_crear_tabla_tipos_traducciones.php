<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_traducciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained('tipos')->cascadeOnDelete();
            $table->string('idioma', 5);
            $table->string('resumen', 255);
            $table->text('texto');
            $table->timestamps();

            $table->unique(['tipo_id', 'idioma']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_traducciones');
    }
};