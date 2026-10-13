<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Habitat extends Model
{
    protected $table = 'habitats';

    protected $fillable = [
        'municipio_id',
        'habitat',
        'hectareas',
    ];

    protected function casts(): array
    {
        return [
            'hectareas' => 'float',
        ];
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
}