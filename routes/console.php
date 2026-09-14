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

Artisan::command('user:password {email} {password}', function ($email, $password) {
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        $this->error("Aucun utilisateur trouve avec l'email {$email}");
        return 1;
    }
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->save();
    $this->info(">>> MOT DE PASSE MIS A JOUR AVEC SUCCES POUR {$email} <<<");
    return 0;
})->purpose('Modifier directement le mot de passe d un utilisateur');

Artisan::command('user:reset-link {email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) {
        $this->error("Aucun utilisateur trouve avec l'email {$email}");
        return 1;
    }
    $token = \Illuminate\Support\Facades\Password::createToken($user);
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $email,
    ], false));
    $this->info(">>> LIEN DE REINITIALISATION POUR {$email} <<<");
    $this->line($url);
    return 0;
})->purpose('Generer instantanement un lien de reinitialisation de mot de passe');

