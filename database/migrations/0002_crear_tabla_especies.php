<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especies', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cientifico', 100)->unique();
            $table->string('grupo', 30);
            $table->string('valoracion', 20);
            $table->string('autor_foto', 150)->nullable();
            $table->string('foto')->nullable();
            $table->string('provincia', 50)->default('Illes Balears');
            $table->string('tipo_clima', 30)->default('mediterrani');
            $table->unsignedTinyInteger('temporada_inicio')->nullable();
            $table->unsignedTinyInteger('temporada_fin')->nullable();
            $table->string('arbolado', 100)->nullable();
            $table->decimal('umbral_api10', 5, 1)->nullable();
            $table->boolean('en_avisos')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('especies');
    }
};
