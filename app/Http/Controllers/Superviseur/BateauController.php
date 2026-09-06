<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bateau;
use App\Models\Pavillon;
use App\Models\Voyage;
use App\Models\Quai;
use Carbon\Carbon;

class BateauController extends Controller
{
    /**
     * Liste des bateaux avec filtres
     */
    public function index(Request $request)
    {
        $query = Bateau::with(['voyages' => function($q) {
            $q->whereIn('statut', ['prevu', 'en_cours']);
        }]);

        // Filtre par statut
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        // Filtre par type
        if ($request->filled('type') && $request->type != 'tous') {
            $query->where('type', $request->type);
        }

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('immatriculation', 'LIKE', "%{$search}%");
            });
        }

        $bateaux = $query->orderBy('nom')->paginate(15);

        // Statistiques
        $statistiques = [
            'total' => Bateau::count(),
            'en_service' => Bateau::where('statut', 'en_service')->count(),
            'en_maintenance' => Bateau::where('statut', 'en_maintenance')->count(),
            'hors_service' => Bateau::where('statut', 'hors_service')->count(),
            'cargo' => Bateau::where('type', 'cargo')->count(),
            'mixte' => Bateau::where('type', 'mixte')->count(),
            'passager' => Bateau::where('type', 'passager')->count(),
        ];

        return view('superviseur.bateaux.index', compact('bateaux', 'statistiques', 'request'));
    }

    /**
     * Détail d'un bateau
     */
    public function show($id)
    {
        $bateau = Bateau::with([
            'pavillons',
            'voyages' => function($q) {
                $q->orderBy('date_depart', 'desc')->limit(5);
            },
            'quais'
        ])->findOrFail($id);

        // Statistiques du bateau
        $stats = [
            'nb_voyages' => $bateau->voyages->count(),
            'nb_voyages_prevus' => $bateau->voyages->where('statut', 'prevu')->count(),
            'nb_reservations' => $bateau->voyages->flatMap->reservations->count(),
            'ca_total' => $bateau->voyages->flatMap->reservations->sum('prix_total') ?? 0,
            'capacite_totale' => $bateau->capacite_totale,
            'capacite_utilisee' => $bateau->voyages->flatMap->reservations->sum('nombre_cargaison') ?? 0,
        ];

        // Quai actuel
        $quaiActuel = $bateau->quais()->first();

        return view('superviseur.bateaux.show', compact('bateau', 'stats', 'quaiActuel'));
    }

    /**
     * Voir les pavillons d'un bateau
     */
    public function pavillons($id)
    {
        $bateau = Bateau::findOrFail($id);
        $pavillons = Pavillon::where('idbateau', $id)->get();

        return view('superviseur.bateaux.pavillons', compact('bateau', 'pavillons'));
    }

    /**
     * Voir l'historique des voyages d'un bateau
     */
    public function historiqueVoyages($id)
    {
        $bateau = Bateau::findOrFail($id);
        $voyages = Voyage::where('idbateau', $id)
            ->orderBy('date_depart', 'desc')
            ->paginate(20);

        return view('superviseur.bateaux.historique', compact('bateau', 'voyages'));
    }

    /**
     * Exporter les bateaux en CSV
     */
    public function export(Request $request)
    {
        $query = Bateau::query();
        
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $bateaux = $query->get();

        $filename = "bateaux_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, [
            'Nom', 'Immatriculation', 'Type', 'Capacité totale', 
            'Capacité passagers', 'Capacité cargaison', 'Statut'
        ]);
        
        foreach ($bateaux as $b) {
            fputcsv($handle, [
                $b->nom,
                $b->immatriculation,
                $b->type,
                $b->capacite_totale,
                $b->capacite_passager,
                $b->capacite_cargaison,
                $b->statut
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