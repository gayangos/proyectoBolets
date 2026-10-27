<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hallazgo extends Model
{
    protected $table = 'hallazgos';

    // Solo se guardan datos cifrados en el navegador: el vector de inicialización y el texto cifrado.
    protected $fillable = [
        'iv',
        'datos',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}