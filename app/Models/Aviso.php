<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aviso extends Model
{
    protected $table = 'avisos';

    protected $fillable = ['municipio_id', 'especie_id', 'fecha', 'api10'];

    protected $casts = [
        'fecha' => 'date',
        'api10' => 'float',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class);
    }
}