<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MensagemAdmin extends Mailable
{
    public function __construct(
        public string $assunto,
        public string $conteudo,
        public string $nomeAdmin,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->assunto);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.mensagem-admin');
    }
}
