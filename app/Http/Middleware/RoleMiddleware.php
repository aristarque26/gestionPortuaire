<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();

        // ✅ Vérification du statut (bloquer les comptes inactifs)
        if ($user->statut === 'inactif') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Contactez l\'administrateur.');
        }

        // Vérifier si le rôle de l'utilisateur est autorisé
        if (!in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}