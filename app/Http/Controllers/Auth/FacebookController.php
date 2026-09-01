<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'prenom' => '',
                    'email' => $facebookUser->getEmail(),
                    'telephone' => '',
                    'password' => bcrypt(uniqid()),
                    'role' => 'client',
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
            return redirect()->route('login')->with('error', 'Erreur de connexion avec Facebook.');
        }
    }
}