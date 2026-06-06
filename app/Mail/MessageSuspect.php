<?php

namespace App\Mail;

use App\Models\MessageAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageSuspect extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public MessageAlert $alert) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activité suspecte détectée — Kimboo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message-suspect',
        );
    }
}
