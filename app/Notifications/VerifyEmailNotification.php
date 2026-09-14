<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class VerifyEmailNotification extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Vérification de votre adresse email · Kimboo')
            ->view('emails.verification-email', [
                'user'            => $notifiable,
                'verificationUrl' => $verificationUrl,
                'expireMinutes'   => Config::get('auth.verification.expire', 60),
            ]);
    }
}
