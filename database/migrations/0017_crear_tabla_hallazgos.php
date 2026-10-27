<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Datos para comprobar la contraseña del cuaderno sin guardarla: la sal con la que
        // el navegador deriva la clave y un texto conocido cifrado con ella.
        Schema::table('users', function (Blueprint $table) {
            $table->string('cuaderno_sal', 44)->nullable()->after('idioma');
            $table->text('cuaderno_verificador')->nullable()->after('cuaderno_sal');
        });

        // Hallazgos cifrados en el navegador: el servidor nunca ve la especie, el lugar ni las notas.
        Schema::create('hallazgos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('iv', 24);
            $table->text('datos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hallazgos');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cuaderno_sal', 'cuaderno_verificador']);
        });
    }
};