<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoursApprouve extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Course $course) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre cours a été approuvé — Kimboo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cours-approuve',
        );
    }
}
