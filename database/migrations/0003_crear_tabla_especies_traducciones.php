<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especies_traducciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->cascadeOnDelete();
            $table->string('idioma', 5);
            $table->string('nombre_comun', 100)->nullable();
            $table->string('habitat', 150)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('palabras_clave')->nullable();
            $table->timestamps();

            $table->unique(['especie_id', 'idioma']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('especies_traducciones');
    }
};