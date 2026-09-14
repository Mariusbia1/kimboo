<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {email=bonjour@kimboo.net}', function ($email) {
    $mailer = config('mail.default');
    $this->info("Mailer actuel : {$mailer}");
    if ($mailer === 'sendmail') {
        $this->info("Sendmail path : " . config('mail.mailers.sendmail.path'));
    }
    $this->info("Envoi d'un email de test vers {$email}...");
    try {
        Mail::raw("Ceci est un email de test Kimboo envoye depuis le serveur.", function ($message) use ($email) {
            $message->to($email)->subject("Test Messagerie Kimboo");
        });
        $this->info(">>> EMAIL ENVOYE AVEC SUCCES A {$email} <<<");
    } catch (\Throwable $e) {
        $this->error("Erreur lors de l'envoi : " . $e->getMessage());
    }
})->purpose('Tester l\'envoi d\'un email');
