<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // Page d'accueil des paramètres client
    public function index()
    {
        return view('client.settings.index');
    }

    // Modifier le profil
    public function profile()
    {
        $user = Auth::user();
        $client = $user->client;
        return view('client.settings.profile', compact('user', 'client'));
    }

    // Mettre à jour le profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $client = $user->client;

        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'nationalite' => 'nullable|string',
            'genre' => 'nullable|string',
        ]);

        // Mise à jour de l'utilisateur
        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        // Mise à jour du client
        if ($client) {
            $client->update([
                'nom' => $request->name,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'nationalite' => $request->nationalite,
                'genre' => $request->genre,
            ]);
        }

        return redirect()->route('client.settings.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    // Page thème
    public function theme()
    {
        return view('client.settings.theme');
    }

    // Mettre à jour le thème
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        // Stocker le thème dans la session ou la base de données
        session(['client_theme' => $request->theme]);

        return redirect()->route('client.settings.theme')->with('success', 'Thème mis à jour avec succès.');
    }
}