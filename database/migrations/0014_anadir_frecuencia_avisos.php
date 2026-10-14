<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('aviso_frecuencia', 10)->default('nunca')->after('is_admin');
            $table->unsignedTinyInteger('aviso_dia_semana')->nullable()->after('aviso_frecuencia');
            $table->unsignedTinyInteger('aviso_cada_dias')->nullable()->after('aviso_dia_semana');
            $table->date('ultimo_aviso')->nullable()->after('aviso_cada_dias');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['aviso_frecuencia', 'aviso_dia_semana', 'aviso_cada_dias', 'ultimo_aviso']);
        });
    }
};