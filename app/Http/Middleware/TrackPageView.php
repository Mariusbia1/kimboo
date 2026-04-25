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
        if ($request->isMethod('GET')) {
            $excludedPrefixes = ['admin', '_debugbar', 'livewire'];
            $path = $request->path();

            $shouldTrack = true;
            foreach ($excludedPrefixes as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    $shouldTrack = false;
                    break;
                }
            }

            if ($shouldTrack) {
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

                // Nouveau visiteur (basé sur cookie)
                $isNewVisitor = !$request->cookie('kimboo_visitor');

                // Source / Referer
                $referer = $request->headers->get('referer');

                PageView::create([
                    'url'          => $request->path(),
                    'route_name'   => $request->route()?->getName(),
                    'ip'           => $ip,
                    'user_agent'   => $userAgent,
                    'user_id'      => auth()->id(),
                    'viewed_at'    => now()->toDateString(),
                    'referer'      => $referer,
                    'device_type'  => $deviceType,
                    'browser'      => $browser,
                    'os'           => $os,
                    'is_new_visitor' => $isNewVisitor,
                ]);
            }
        }

        $response = $next($request);

        // Poser le cookie visiteur (valable 1 an)
        if (!$request->cookie('kimboo_visitor')) {
            $response->cookie('kimboo_visitor', '1', 60 * 24 * 365);
        }

        return $response;
    }
}
