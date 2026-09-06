<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Trajet;
use App\Models\Reservation;
use Carbon\Carbon;

class VoyageController extends Controller
{
    /**
     * Liste des voyages avec filtres
     */
    public function index(Request $request)
    {
        $query = Voyage::with('bateau');

        // Filtre par statut
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        // Filtre par bateau
        if ($request->filled('bateau_id')) {
            $query->where('idbateau', $request->bateau_id);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_depart', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_depart', '<=', $request->date_fin);
        }

        // Filtre par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourdhui':
                    $query->whereDate('date_depart', Carbon::today());
                    break;
                case 'semaine':
                    $query->whereBetween('date_depart', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'mois':
                    $query->whereMonth('date_depart', Carbon::now()->month)
                          ->whereYear('date_depart', Carbon::now()->year);
                    break;
            }
        }

        $voyages = $query->orderBy('date_depart', 'desc')->paginate(15);

        // Statistiques
        $statistiques = [
            'total' => Voyage::count(),
            'prevu' => Voyage::where('statut', 'prevu')->count(),
            'en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'termine' => Voyage::where('statut', 'termine')->count(),
            'annule' => Voyage::where('statut', 'annule')->count(),
            'prochains' => Voyage::where('statut', 'prevu')
                ->whereDate('date_depart', '>=', Carbon::today())
                ->count(),
        ];

        // Bateaux pour le filtre
        $bateaux = Bateau::all();

        return view('superviseur.voyages.index', compact('voyages', 'statistiques', 'request', 'bateaux'));
    }

    /**
     * Détail d'un voyage
     */
    public function show($id)
    {
        $voyage = Voyage::with([
            'bateau',
            'trajets.ports',
            'reservations.client',
            'reservations.pavillon'
        ])->findOrFail($id);

        // Statistiques du voyage
        $stats = [
            'nb_reservations' => $voyage->reservations->count(),
            'nb_passagers' => $voyage->reservations->where('type_reservation', 'passage')->sum('nombre_cargaison') ?? 0,
            'nb_cargaison' => $voyage->reservations->where('type_reservation', 'cargaison')->sum('poids_cargaison') ?? 0,
            'ca_total' => $voyage->reservations->sum('prix_total') ?? 0,
            'par_statut' => $voyage->reservations->groupBy('statut')->map->count(),
        ];

        return view('superviseur.voyages.show', compact('voyage', 'stats'));
    }

    /**
     * Voir les réservations d'un voyage
     */
    public function reservations($id)
    {
        $voyage = Voyage::with('bateau')->findOrFail($id);
        $reservations = Reservation::with('client')
            ->where('idvoyage', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('superviseur.voyages.reservations', compact('voyage', 'reservations'));
    }

    /**
     * Voir les trajets d'un voyage
     */
    public function trajets($id)
    {
        $voyage = Voyage::with('bateau')->findOrFail($id);
        $trajets = Trajet::with('ports')
            ->where('idvoyage', $id)
            ->orderBy('ordre')
            ->get();

        return view('superviseur.voyages.trajets', compact('voyage', 'trajets'));
    }

    /**
     * Exporter les voyages en CSV
     */
    public function export(Request $request)
    {
        $query = Voyage::with('bateau');
        
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $voyages = $query->get();

        $filename = "voyages_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, [
            'Code', 'Description', 'Bateau', 'Date départ', 
            'Statut', 'Nombre réservations', 'CA total'
        ]);
        
        foreach ($voyages as $v) {
            $nbReservations = $v->reservations->count();
            $caTotal = $v->reservations->sum('prix_total') ?? 0;
            
            fputcsv($handle, [
                $v->code_voyage,
                $v->description ?? '',
                $v->bateau->nom ?? 'N/A',
                Carbon::parse($v->date_depart)->format('d/m/Y H:i'),
                $v->statut,
                $nbReservations,
                number_format($caTotal, 2, ',', ' ')
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