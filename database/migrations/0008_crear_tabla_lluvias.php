<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lluvias', function (Blueprint $table) {
            $table->id();
            $table->string('estacion', 10);
            $table->date('fecha');
            $table->decimal('precipitacion', 5, 1);
            $table->timestamps();

            $table->unique(['estacion', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lluvias');
    }
};