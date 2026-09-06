<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirection selon le rôle
        $user = Auth::user();
        
        // ✅ ADMIN → dashboard admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // ✅ CLIENT → dashboard client
        if ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }

        // ✅ PERSONNEL → redirection selon personnel_role
        if ($user->isPersonnel()) {
            // Charger la relation personnel
            $user->load('personnel');
            
            if ($user->personnel) {
                $role = $user->personnel->personnel_role;
                
                switch ($role) {
                    case 'superviseur':
                        return redirect()->route('superviseur.dashboard');
                        break;
                    case 'comptable':
                        return redirect()->route('comptable.dashboard');
                        break;
                    case 'caissier':
                        return redirect()->route('caissier.dashboard');
                        break;
                    case 'gestionnaire':
                        return redirect()->route('gestionnaire.dashboard');
                        break;
                    case 'agent_portuaire':
                    default:
                        return redirect()->route('agent.dashboard');
                        break;
                }
            }
            
            // Fallback si la relation personnel n'existe pas
            return redirect()->route('personnel.dashboard');
        }
        
        // Par défaut (client ou autre)
        return redirect()->route('client.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}