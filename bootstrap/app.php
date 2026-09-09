<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\TrackPageView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Votre session a expiré. Veuillez recharger la page.'], 419);
            }
            return redirect()->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->with('error', 'Votre session a expiré pour des raisons de sécurité. Vos données ont été conservées, veuillez cliquer à nouveau sur le bouton pour enregistrer.');
        });

        $exceptions->render(function (TransportExceptionInterface $e, $request) {
            \Illuminate\Support\Facades\Log::warning('Transport email indisponible ou saturé: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Opération enregistrée avec succès. L\'email de notification n\'a pas pu être envoyé.'], 200);
            }
            return redirect()->back()
                ->with('warning', 'Votre action a été enregistrée avec succès. (La notification email automatique n\'a pas pu être envoyée temporairement).');
        });
    })->create();

