<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Client;
use App\Models\Pavillon;
use Carbon\Carbon;

class AgentController extends Controller
{
    /**
     * Affiche le tableau de bord de l'agent portuaire
     */
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('personnel');

        // Statistiques spécifiques à l'agent
        $stats = [
            'reservations_aujourdhui' => Reservation::whereDate('created_at', Carbon::today())->count(),
            'reservations_en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'reservations_confirmees' => Reservation::where('statut', 'confirme')->count(),
            'reservations_arrivees' => Reservation::where('statut', 'arrive')->count(),
            'voyages_prevus' => Voyage::where('statut', 'prevu')->count(),
            'voyages_en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'bateaux_service' => Bateau::where('statut', 'en_service')->count(),
        ];

        // Dernières réservations
        $dernieresReservations = Reservation::with(['client', 'voyage.bateau'])
            ->latest()
            ->take(10)
            ->get();

        // Prochains voyages
        $prochainsVoyages = Voyage::with('bateau')
            ->where('statut', 'prevu')
            ->where('date_depart', '>=', Carbon::now())
            ->orderBy('date_depart')
            ->take(5)
            ->get();

        return view('personnel.agent.dashboard', compact(
            'user',
            'stats',
            'dernieresReservations',
            'prochainsVoyages'
        ));
    }

    /**
     * Liste des réservations (pour l'agent)
     */
    public function reservations(Request $request)
    {
        $query = Reservation::with(['client', 'voyage.bateau', 'pavillon']);

        // Filtres
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques
        $statistiques = [
            'total' => Reservation::count(),
            'en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'confirme' => Reservation::where('statut', 'confirme')->count(),
            'paye' => Reservation::where('statut', 'paye')->count(),
            'arrive' => Reservation::where('statut', 'arrive')->count(),
            'annule' => Reservation::where('statut', 'annule')->count(),
        ];

        return view('personnel.agent.reservations', compact('reservations', 'statistiques', 'request'));
    }

    /**
     * Détail d'une réservation
     */
    public function showReservation($id)
    {
        $reservation = Reservation::with([
            'client',
            'voyage.bateau',
            'voyage.trajets.ports',
            'pavillon',
            'paiements'
        ])->findOrFail($id);

        return view('personnel.agent.reservation-show', compact('reservation'));
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
     * Confirmer une réservation
     */
    public function confirmerReservation($id)
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
    public function annulerReservation($id)
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
     * Exporter les réservations en CSV
     */
    public function exportReservations(Request $request)
    {
        $query = Reservation::with(['client', 'voyage.bateau']);

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $reservations = $query->get();

        $filename = "reservations_agent_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['ID', 'Client', 'Bateau', 'Type', 'Date réservation', 'Date embarquement', 'Prix total', 'Statut']);

        foreach ($reservations as $res) {
            fputcsv($handle, [
                $res->id,
                $res->client->nom . ' ' . $res->client->prenom,
                $res->voyage->bateau->nom ?? 'N/A',
                $res->type_reservation,
                Carbon::parse($res->date_reservation)->format('d/m/Y H:i'),
                Carbon::parse($res->date_embarquement)->format('d/m/Y H:i'),
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