<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageView;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        // Suivi du temps passé pour l'utilisateur connecté (en session, rapide en mémoire)
        if (auth()->check()) {
            $user = auth()->user();
            $lastActive = session('user_last_active_at');
            $now = now();

            if ($lastActive) {
                $lastActiveCarbon = \Carbon\Carbon::parse($lastActive);
                $diff = $now->diffInSeconds($lastActiveCarbon);
                if ($diff > 0 && $diff <= 120) {
                    $user->increment('time_spent_seconds', $diff);
                }
            }

            session(['user_last_active_at' => $now]);

            if (!$user->last_login_at) {
                $user->update(['last_login_at' => $now]);
            }
        }

        $response = $next($request);

        // Poser le cookie visiteur (valable 1 an)
        if (!$request->cookie('kimboo_visitor')) {
            $response->cookie('kimboo_visitor', '1', 60 * 24 * 365);
        }

        return $response;
    }

    /**
     * Exécuté après que la réponse a été envoyée au navigateur du client.
     * N'ajoute aucun délai au temps de chargement perçu par l'utilisateur.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (!$request->isMethod('GET')) {
            return;
        }

        try {
            $excludedPrefixes = ['admin', '_debugbar', 'livewire', 'api', 'storage', 'build'];
            $path = $request->path();

            foreach ($excludedPrefixes as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    return;
                }
            }

            $userAgent = $request->userAgent() ?? '';
            $ip = $request->ip();

            // Device type
            $deviceType = 'desktop';
            if (preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent)) {
                $deviceType = preg_match('/iPad/i', $userAgent) ? 'tablet' : 'mobile';
            }

            // Browser
            $browser = 'Autre';
            if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) $browser = 'Chrome';
            elseif (str_contains($userAgent, 'Firefox')) $browser = 'Firefox';
            elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) $browser = 'Safari';
            elseif (str_contains($userAgent, 'Edg')) $browser = 'Edge';
            elseif (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) $browser = 'Opera';

            // OS
            $os = 'Autre';
            if (str_contains($userAgent, 'Windows')) $os = 'Windows';
            elseif (str_contains($userAgent, 'Mac OS')) $os = 'macOS';
            elseif (str_contains($userAgent, 'Linux')) $os = 'Linux';
            elseif (str_contains($userAgent, 'Android')) $os = 'Android';
            elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) $os = 'iOS';

            $isNewVisitor = !$request->cookie('kimboo_visitor');
            $referer = $request->headers->get('referer');

            PageView::create([
                'url'            => $request->path(),
                'route_name'     => $request->route()?->getName(),
                'ip'             => $ip,
                'user_agent'     => $userAgent,
                'user_id'        => auth()->id(),
                'viewed_at'      => now()->toDateString(),
                'referer'        => $referer,
                'device_type'    => $deviceType,
                'browser'        => $browser,
                'os'             => $os,
                'is_new_visitor' => $isNewVisitor,
            ]);
        } catch (\Throwable $e) {
            // Ignorer silencieusement pour ne jamais impacter l'expérience utilisateur
        }
    }
}
