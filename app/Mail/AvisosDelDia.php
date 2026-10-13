<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AvisosDelDia extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * $avisos llega agrupado por nombre de municipio.
     */
    public function __construct(
        public User $usuario,
        public Collection $avisos,
        public string $fecha,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Avisos de bolets'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'correo.avisos',
            with: [
                // El índice del día D usa la lluvia hasta D-1.
                'dataPluja' => Carbon::parse($this->fecha)->subDay()->format('d/m/Y'),
            ],
        );
    }
}