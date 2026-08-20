<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'prenom' => '',
                    'email' => $googleUser->getEmail(),
                    'telephone' => '',
                    'password' => bcrypt(uniqid()),
                    'role' => 'personnel',
                    'statut' => 'actif',
                ]);

                Client::create([
                    'nom' => $user->name,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'adresse' => 'Non renseignée',
                    'nationalite' => 'Non renseignée',
                    'genre' => 'Homme',
                    'photo' => null,
                    'date_inscription' => now(),
                    'statut' => 'actif',
                    'idutilisateur' => $user->id,
                ]);
            }
            
            Auth::login($user);
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('client.dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur de connexion avec Google.');
        }
    }
}