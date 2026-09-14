<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $viewPath = sys_get_temp_dir() . '/kimboo_views';
        if (!is_dir($viewPath)) {
            @mkdir($viewPath, 0777, true);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('fr');

        \Illuminate\Support\Facades\Mail::extend('phpmail', function () {
            return new \App\Mail\PhpMailTransport();
        });

        // Ignorer l'avertissement bénin tempnam() propre aux hébergements mutualisés (OVH)
        set_error_handler(function ($errno, $errstr) {
            if (str_contains($errstr, 'tempnam()')) {
                return true;
            }
            return false;
        }, E_NOTICE | E_WARNING);

        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Réinitialisation de votre mot de passe · Kimboo')
                ->view('emails.reinitialisation-mot-de-passe', [
                    'user'     => $notifiable,
                    'resetUrl' => $resetUrl,
                    'count'    => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
                ]);
        });

        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Vérification de votre adresse email · Kimboo')
                ->view('emails.verification-email', [
                    'user'            => $notifiable,
                    'verificationUrl' => $url,
                    'expireMinutes'   => config('auth.verification.expire', 60),
                ]);
        });
    }
}
