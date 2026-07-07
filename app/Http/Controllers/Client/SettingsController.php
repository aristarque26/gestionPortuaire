<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Reservation;
use App\Models\Paiement;

class SettingsController extends Controller
{
    // Page d'accueil des paramètres client
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;

        // Statistiques des réservations
        $totalReservations = Reservation::where('idclient', $client->id)->count();
        $reservationsConfirmees = Reservation::where('idclient', $client->id)
                                ->where('statut', 'confirme')->count();
        $reservationsEnAttente = Reservation::where('idclient', $client->id)
                                ->where('statut', 'en_attente')->count();

        // Statistiques des paiements
        $totalPaiements = Paiement::whereHas('reservation', function($q) use ($client) {
            $q->where('idclient', $client->id);
        })->count();

        // Dernières réservations et paiements
        $dernieresReservations = Reservation::where('idclient', $client->id)
                                ->latest()->take(5)->get();
        $derniersPaiements = Paiement::whereHas('reservation', function($q) use ($client) {
            $q->where('idclient', $client->id);
        })->latest()->take(5)->get();

        return view('client.settings.index', compact(
            'user', 
            'client', 
            'totalReservations', 
            'reservationsConfirmees', 
            'reservationsEnAttente',
            'totalPaiements',
            'dernieresReservations',
            'derniersPaiements'
        ));
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

    // 🔐 Page de changement de mot de passe
    public function password()
    {
        return view('client.settings.password');
    }

    // 🔐 Mettre à jour le mot de passe
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.password')
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }
}