<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauMessageRecu extends Mailable
{
    use Queueable, SerializesModels;

    public Message $chatMessage;

    public function __construct(Message $chatMessage)
    {
        if (!$chatMessage->relationLoaded('sender') || !$chatMessage->relationLoaded('receiver')) {
            $chatMessage->load(['sender', 'receiver']);
        }
        $this->chatMessage = $chatMessage;
    }

    public function envelope(): Envelope
    {
        $senderName = $this->chatMessage->sender?->name ?? 'Un utilisateur';

        return new Envelope(
            subject: 'Nouveau message de ' . $senderName . ' — Kimboo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nouveau-message-recu',
            with: [
                'chatMessage' => $this->chatMessage,
                'sender'      => $this->chatMessage->sender,
                'receiver'    => $this->chatMessage->receiver,
            ],
        );
    }
}
