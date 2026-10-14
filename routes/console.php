<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Proceso diario de avisos: lluvia de AEMET, índice API10 y avisos.
Schedule::command('lluvia:descargar')->dailyAt('07:00')->timezone('Europe/Madrid')->appendOutputTo(storage_path('logs/scheduler.log'));
Schedule::command('api10:calcular')->dailyAt('07:10')->timezone('Europe/Madrid')->appendOutputTo(storage_path('logs/scheduler.log'));
Schedule::command('avisos:generar')->dailyAt('07:20')->timezone('Europe/Madrid')->appendOutputTo(storage_path('logs/scheduler.log'));
Schedule::command('avisos:enviar')->dailyAt('07:30')->timezone('Europe/Madrid')->appendOutputTo(storage_path('logs/scheduler.log'));