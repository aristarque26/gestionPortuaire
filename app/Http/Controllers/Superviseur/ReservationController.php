<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Voyage;
use App\Models\Pavillon;
use App\Models\Paiement;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Liste des réservations avec filtres
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'voyage.bateau', 'pavillon', 'paiements']);

        // Filtre par statut
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        // Filtre par type
        if ($request->filled('type') && $request->type != 'tous') {
            $query->where('type_reservation', $request->type);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_reservation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_reservation', '<=', $request->date_fin);
        }

        // Filtre par client
        if ($request->filled('client')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('nom', 'LIKE', "%{$request->client}%")
                  ->orWhere('prenom', 'LIKE', "%{$request->client}%")
                  ->orWhere('email', 'LIKE', "%{$request->client}%");
            });
        }

        // Filtre par bateau
        if ($request->filled('bateau')) {
            $query->whereHas('voyage.bateau', function($q) use ($request) {
                $q->where('nom', 'LIKE', "%{$request->bateau}%");
            });
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques pour le filtre
        $statistiques = [
            'total' => Reservation::count(),
            'en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'confirme' => Reservation::where('statut', 'confirme')->count(),
            'paye' => Reservation::where('statut', 'paye')->count(),
            'arrive' => Reservation::where('statut', 'arrive')->count(),
            'annule' => Reservation::where('statut', 'annule')->count(),
        ];

        return view('superviseur.reservations.index', compact('reservations', 'statistiques', 'request'));
    }

    /**
     * Détail d'une réservation
     */
    public function show($id)
    {
        $reservation = Reservation::with([
            'client',
            'voyage.bateau',
            'voyage.trajets.ports',
            'pavillon',
            'paiements'
        ])->findOrFail($id);

        return view('superviseur.reservations.show', compact('reservation'));
    }

    /**
     * Confirmer une réservation
     */
    public function confirmer($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->statut === 'annule') {
            return redirect()->back()->with('error', 'Impossible de confirmer une réservation annulée.');
        }

        $reservation->statut = 'confirme';
        $reservation->save();

        return redirect()->back()->with('success', 'Réservation confirmée avec succès.');
    }

    /**
     * Annuler une réservation
     */
    public function annuler($id, Request $request)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->statut === 'arrive') {
            return redirect()->back()->with('error', 'Impossible d\'annuler une réservation déjà arrivée.');
        }

        $reservation->statut = 'annule';
        $reservation->save();

        return redirect()->back()->with('success', 'Réservation annulée avec succès.');
    }

    /**
     * Marquer une réservation comme arrivée
     */
    public function marquerArrivee($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->statut === 'annule') {
            return redirect()->back()->with('error', 'Impossible de marquer une réservation annulée comme arrivée.');
        }

        $reservation->statut = 'arrive';
        $reservation->date_arrivee = Carbon::now();
        $reservation->save();

        return redirect()->back()->with('success', 'Réservation marquée comme arrivée.');
    }

    /**
     * Marquer une réservation comme payée
     */
    public function marquerPayee($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->statut === 'annule') {
            return redirect()->back()->with('error', 'Impossible de marquer une réservation annulée comme payée.');
        }

        $reservation->statut = 'paye';
        $reservation->save();

        return redirect()->back()->with('success', 'Réservation marquée comme payée.');
    }

    /**
     * Exporter les réservations en CSV
     */
    public function export(Request $request)
    {
        // Récupérer les réservations filtrées
        $query = Reservation::with(['client', 'voyage.bateau']);
        
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $reservations = $query->get();

        // Créer le fichier CSV
        $filename = "reservations_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        // En-têtes
        fputcsv($handle, [
            'ID', 'Client', 'Email', 'Téléphone', 'Bateau', 
            'Type', 'Date réservation', 'Date embarquement', 
            'Prix total', 'Statut'
        ]);
        
        // Données
        foreach ($reservations as $res) {
            fputcsv($handle, [
                $res->id,
                $res->client->nom . ' ' . $res->client->prenom,
                $res->client->email,
                $res->client->telephone,
                $res->voyage->bateau->nom ?? 'N/A',
                $res->type_reservation,
                $res->date_reservation,
                $res->date_embarquement,
                number_format($res->prix_total, 2, ',', ' '),
                $res->statut
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}