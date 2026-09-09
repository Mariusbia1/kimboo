<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSuspended
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_suspended && !Auth::user()->isAdmin()) {
            $reason = Auth::user()->suspension_reason;
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $errorMsg = 'Votre compte a été suspendu par l\'administration' . ($reason ? ' (Motif : ' . $reason . ')' : '.') . ' Pour toute réclamation, contactez support@kimboo.net.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $errorMsg], 403);
            }

            return redirect()->route('login')->with('error', $errorMsg);
        }

        return $next($request);
    }
}
