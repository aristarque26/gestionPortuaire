<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use App\Models\Port;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des voyages avec filtres
        $query = Voyage::where('statut', 'prevu')
            ->where('date_depart', '>', now())
            ->with('bateau');

        // Filtre par port
        if ($request->filled('port')) {
            $query->whereHas('trajets.ports', function($q) use ($request) {
                $q->where('ports.id', $request->port);
            });
        }

        // Filtre par date début
        if ($request->filled('date_debut')) {
            $query->whereDate('date_depart', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->filled('date_fin')) {
            $query->whereDate('date_depart', '<=', $request->date_fin);
        }

        // Filtre par recherche (code voyage ou bateau)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code_voyage', 'LIKE', "%{$search}%")
                  ->orWhereHas('bateau', function($b) use ($search) {
                      $b->where('nom', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Tri
        switch ($request->sort) {
            case 'date_desc':
                $query->orderBy('date_depart', 'desc');
                break;
            case 'prix_asc':
                $query->orderBy('prix_total', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix_total', 'desc');
                break;
            default:
                $query->orderBy('date_depart', 'asc');
                break;
        }

        // Pagination (10 par page)
        $voyages = $query->paginate(10);

        // Récupération des ports pour le filtre
        $ports = Port::all();

        return view('client.voyages.index', compact('voyages', 'ports'));
    }

    /**
     * Afficher les détails d'un voyage
     */
    public function show($id)
    {
        $voyage = Voyage::with(['bateau', 'trajets.ports.quais'])->findOrFail($id);
        return view('client.voyages.show', compact('voyage'));
    }
}