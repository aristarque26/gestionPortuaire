<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /* Documentation complète de l'application - toutes les fonctionnalités expliquées */

    public function index(Request $request)
    {
        $query = Reservation::with('client', 'voyage', 'pavillon');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_reservation', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_reservation', '<=', $request->date_fin);
        }

        $reservations = $query->latest()->paginate(10);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::with('client', 'voyage', 'pavillon', 'paiement')->findOrFail($id);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,confirme,annule,arrive'
        ]);

        $reservation->update(['statut' => $request->statut]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Statut de la réservation mis à jour.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation supprimée avec succès.');
    }

    public static function countEnAttente()
    {
        return Reservation::where('statut', 'en_attente')->count();
    }

    public function confirmer($id)
    {
        // 🔍 LOG : début de la méthode
        \Log::info('🔍 Méthode confirmer() déclenchée pour la réservation ' . $id);

        $reservation = Reservation::findOrFail($id);

        // Vérifier que la réservation est bien en attente
        if ($reservation->statut !== 'en_attente') {
            \Log::warning('⚠️ Réservation ' . $id . ' non en attente (statut: ' . $reservation->statut . ')');
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Cette réservation ne peut pas être confirmée.');
        }

        // Changer le statut
        $reservation->statut = 'confirme';
        $reservation->save();

        \Log::info('✅ Réservation ' . $id . ' confirmée, envoi de l\'email en cours...');

        // Envoi de l'email au client (avec template Blade)
        try {
            $client = $reservation->client;

            $sujet = "✅ Votre réservation n°{$reservation->id} est confirmée - KivuPort";

            // Utilisation du template Blade
            $contenu = view('emails.reservation-confirmation', ['reservation' => $reservation])->render();

            \Log::info('📧 Tentative d\'envoi à ' . $client->email);

            \App\Services\BrevoEmailService::send($client->email, $sujet, $contenu);

            \Log::info('✅ Email envoyé avec succès à ' . $client->email);

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Réservation confirmée et email envoyé au client.');

        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('admin.reservations.index')
                ->with('warning', 'Réservation confirmée mais l\'email n\'a pas pu être envoyé.');
        }
    }
}