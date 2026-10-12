<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Temperatura media del día; la lluvia pasa a admitir nulos porque hay días con solo uno de los dos datos.
        Schema::table('lluvias', function (Blueprint $table) {
            $table->decimal('precipitacion', 5, 1)->nullable()->change();
            $table->decimal('temperatura', 4, 1)->nullable()->after('precipitacion');
        });

        // Temperatura media de los últimos días, junto al API10.
        Schema::table('indices', function (Blueprint $table) {
            $table->decimal('temperatura', 4, 1)->nullable()->after('api10');
        });
    }

    public function down(): void
    {
        Schema::table('indices', function (Blueprint $table) {
            $table->dropColumn('temperatura');
        });

        Schema::table('lluvias', function (Blueprint $table) {
            $table->dropColumn('temperatura');
            $table->decimal('precipitacion', 5, 1)->nullable(false)->change();
        });
    }
};