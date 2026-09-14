<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe · Kimboo')
            ->view('emails.reinitialisation-mot-de-passe', [
                'user'     => $notifiable,
                'resetUrl' => $resetUrl,
                'count'    => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
            ]);
    }
}
