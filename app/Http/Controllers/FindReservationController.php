<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class FindReservationController extends Controller
{
    // Afficher le formulaire email
    public function showForm()
    {
        return view('auth.find');
    }

    // Envoyer le code de vérification
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $code = rand(100000, 999999);

        // Stocker le code dans cache (valable 10 minutes)
        Cache::put('verif_code_' . $user->id, $code, 600);

        // Envoyer le code par email (à activer avec Brevo plus tard)
        // Temporairement, on se contente d’afficher le code en console ou de le stocker en session
        session(['verif_email' => $user->email, 'verif_user_id' => $user->id]);

        // Pour le test sans email réel, on affiche le code dans la session
        return redirect()->route('find.verify.form')->with('code', $code);
    }

    // Afficher le formulaire de saisie du code
    public function showVerifyForm()
    {
        if (!session('verif_email')) {
            return redirect()->route('find.form');
        }
        return view('auth.verify-code');
    }

    // Vérifier le code et afficher les réservations
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6'
        ]);

        $userId = session('verif_user_id');
        $storedCode = Cache::get('verif_code_' . $userId);

        if (!$storedCode || $storedCode != $request->code) {
            return back()->with('error', 'Code invalide ou expiré.');
        }

        // Code valide : on supprime le code et on récupère les réservations
        Cache::forget('verif_code_' . $userId);
        
        $client = \App\Models\Client::where('idutilisateur', $userId)->first();
        if (!$client) {
            return redirect()->route('find.form')->with('error', 'Aucun client associé à cet email.');
        }

        $reservations = Reservation::where('idclient', $client->id)->with('voyage', 'pavillon')->get();
        
        session()->forget(['verif_email', 'verif_user_id']);
        
        return view('client.found-reservations', compact('reservations'));
    }
}