<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationAnnulee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking, public string $annulePar = 'eleve') {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réservation annulée — Kimboo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-annulee',
        );
    }
}
