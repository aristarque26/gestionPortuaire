<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
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
        $reservation = Reservation::findOrFail($id);
        
        // Vérifier que la réservation est bien en attente
        if ($reservation->statut !== 'en_attente') {
            return redirect()->route('admin.reservations.index')
                ->with('error', 'Cette réservation ne peut pas être confirmée.');
        }
        
        // Changer le statut
        $reservation->statut = 'confirme';
        $reservation->save();
        
        // Envoi de l'email au client
        try {
            $client = $reservation->client;
            $voyage = $reservation->voyage;
            
            $sujet = "Votre réservation est confirmée - Gestion Portuaire";
            
            $contenu = "
                <html>
                <head><title>Confirmation de réservation</title></head>
                <body>
                    <h1>Bonjour {$client->prenom} {$client->nom}</h1>
                    <p>Votre réservation <strong>n°{$reservation->id}</strong> a été confirmée par notre équipe.</p>
                    <p><strong>Détails du voyage :</strong><br>
                    - Code voyage : {$voyage->code_voyage}<br>
                    - Type : {$reservation->type_reservation}<br>
                    - Date d'embarquement : {$reservation->date_embarquement}<br>
                    - Prix total : {$reservation->prix_total} €
                    </p>
                    <p>Veuillez procéder au paiement pour finaliser votre réservation.</p>
                    <p>Merci de votre confiance.</p>
                    <hr>
                    <small>Gestion Portuaire - Votre partenaire maritime</small>
                </body>
                </html>
            ";
            
            \App\Services\BrevoEmailService::send($client->email, $sujet, $contenu);
            
            return redirect()->route('admin.reservations.index')
                ->with('success', 'Réservation confirmée et email envoyé au client.');
                
        } catch (\Exception $e) {
            // L'email n'a pas été envoyé mais la réservation est confirmée
            return redirect()->route('admin.reservations.index')
                ->with('warning', 'Réservation confirmée mais l\'email n\'a pas pu être envoyé.');
        }
    }
}