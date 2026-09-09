<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Non authentifié.'], 401);
            }
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cet espace.');
        }

        if (auth()->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès non autorisé pour votre rôle.'], 403);
            }

            $userRole = auth()->user()->role;
            $roleNames = [
                'eleve'      => 'élèves',
                'professeur' => 'professeurs',
                'admin'      => 'administrateurs',
            ];
            $targetRoleLabel = $roleNames[$role] ?? $role;

            $redirectRoute = match ($userRole) {
                'admin'      => 'admin.dashboard',
                'professeur' => 'professeur.dashboard',
                'eleve'      => 'eleve.dashboard',
                default      => 'home',
            };

            return redirect()->route($redirectRoute)
                ->with('error', "Cette section est réservée aux comptes {$targetRoleLabel}.");
        }

        return $next($request);
    }
}

