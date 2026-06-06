<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelAvis extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vous avez reçu un nouvel avis — Kimboo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nouvel-avis',
        );
    }
}
