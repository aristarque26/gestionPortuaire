<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Port;
use App\Models\Quai;
use App\Models\Trajet;
use App\Models\Pavillon;
use App\Models\Reservation;
use Carbon\Carbon;

class GestionnaireController extends Controller
{
    /**
     * Affiche le tableau de bord du gestionnaire
     */
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('personnel');

        // Statistiques
        $stats = [
            'voyages_prevus' => Voyage::where('statut', 'prevu')->count(),
            'voyages_en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'voyages_termines' => Voyage::where('statut', 'termine')->count(),
            'voyages_annules' => Voyage::where('statut', 'annule')->count(),
            'total_voyages' => Voyage::count(),

            'bateaux_service' => Bateau::where('statut', 'en_service')->count(),
            'bateaux_maintenance' => Bateau::where('statut', 'en_maintenance')->count(),
            'bateaux_hors_service' => Bateau::where('statut', 'hors_service')->count(),
            'total_bateaux' => Bateau::count(),

            'ports_actifs' => Port::where('statut', 'actif')->count(),
            'total_ports' => Port::count(),

            'quais_libres' => Quai::where('statut', 'libre')->count(),
            'quais_occupes' => Quai::where('statut', 'occupe')->count(),
            'total_quais' => Quai::count(),
        ];

        // Prochains voyages
        $prochainsVoyages = Voyage::with('bateau')
            ->where('statut', 'prevu')
            ->where('date_depart', '>=', Carbon::now())
            ->orderBy('date_depart')
            ->take(5)
            ->get();

        // Derniers voyages terminés
        $derniersVoyages = Voyage::with('bateau')
            ->where('statut', 'termine')
            ->orderBy('date_depart', 'desc')
            ->take(5)
            ->get();

        // Bateaux en maintenance
        $bateauxMaintenance = Bateau::where('statut', 'en_maintenance')->get();

        return view('personnel.gestionnaire.dashboard', compact(
            'user',
            'stats',
            'prochainsVoyages',
            'derniersVoyages',
            'bateauxMaintenance'
        ));
    }

    /**
     * Liste des voyages
     */
    public function voyages(Request $request)
    {
        $query = Voyage::with('bateau');

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('bateau_id')) {
            $query->where('idbateau', $request->bateau_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_depart', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_depart', '<=', $request->date_fin);
        }

        $voyages = $query->orderBy('date_depart', 'desc')->paginate(15);

        $bateaux = Bateau::all();

        return view('personnel.gestionnaire.voyages', compact('voyages', 'request', 'bateaux'));
    }

    /**
     * Détail d'un voyage
     */
    public function showVoyage($id)
    {
        $voyage = Voyage::with([
            'bateau',
            'trajets.ports',
            'reservations.client',
            'reservations.pavillon'
        ])->findOrFail($id);

        $stats = [
            'nb_reservations' => $voyage->reservations->count(),
            'ca_total' => $voyage->reservations->sum('prix_total') ?? 0,
            'par_statut' => $voyage->reservations->groupBy('statut')->map->count(),
        ];

        return view('personnel.gestionnaire.voyage-show', compact('voyage', 'stats'));
    }

    /**
     * Créer un voyage
     */
    public function createVoyage()
    {
        $bateaux = Bateau::where('statut', 'en_service')->get();
        return view('personnel.gestionnaire.voyage-create', compact('bateaux'));
    }

    /**
     * Enregistrer un voyage
     */
    public function storeVoyage(Request $request)
    {
        $validated = $request->validate([
            'code_voyage' => 'required|string|max:50|unique:voyages',
            'description' => 'nullable|string|max:255',
            'date_depart' => 'required|date|after:now',
            'idbateau' => 'required|exists:bateaux,id',
        ]);

        $validated['statut'] = 'prevu';

        $voyage = Voyage::create($validated);

        return redirect()->route('gestionnaire.voyages.show', $voyage->id)
            ->with('success', 'Voyage créé avec succès.');
    }

    /**
     * Modifier un voyage
     */
    public function editVoyage($id)
    {
        $voyage = Voyage::findOrFail($id);
        $bateaux = Bateau::all();

        if ($voyage->statut === 'termine' || $voyage->statut === 'annule') {
            return redirect()->back()->with('error', 'Impossible de modifier un voyage terminé ou annulé.');
        }

        return view('personnel.gestionnaire.voyage-edit', compact('voyage', 'bateaux'));
    }

    /**
     * Mettre à jour un voyage
     */
    public function updateVoyage(Request $request, $id)
    {
        $voyage = Voyage::findOrFail($id);

        if ($voyage->statut === 'termine' || $voyage->statut === 'annule') {
            return redirect()->back()->with('error', 'Impossible de modifier un voyage terminé ou annulé.');
        }

        $validated = $request->validate([
            'code_voyage' => 'required|string|max:50|unique:voyages,code_voyage,' . $id,
            'description' => 'nullable|string|max:255',
            'date_depart' => 'required|date',
            'idbateau' => 'required|exists:bateaux,id',
            'statut' => 'required|in:prevu,en_cours,termine,annule',
        ]);

        $voyage->update($validated);

        return redirect()->route('gestionnaire.voyages.show', $voyage->id)
            ->with('success', 'Voyage mis à jour avec succès.');
    }

    /**
     * Supprimer un voyage
     */
    public function deleteVoyage($id)
    {
        $voyage = Voyage::findOrFail($id);

        if ($voyage->reservations()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un voyage qui a des réservations.');
        }

        $voyage->delete();

        return redirect()->route('gestionnaire.voyages')
            ->with('success', 'Voyage supprimé avec succès.');
    }

    /**
     * Liste des bateaux
     */
    public function bateaux(Request $request)
    {
        $query = Bateau::query();

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type') && $request->type != 'tous') {
            $query->where('type', $request->type);
        }

        $bateaux = $query->orderBy('nom')->paginate(15);

        return view('personnel.gestionnaire.bateaux', compact('bateaux', 'request'));
    }

    /**
     * Détail d'un bateau
     */
    public function showBateau($id)
    {
        $bateau = Bateau::with([
            'pavillons',
            'voyages' => function($q) {
                $q->orderBy('date_depart', 'desc')->limit(5);
            }
        ])->findOrFail($id);

        $stats = [
            'nb_voyages' => $bateau->voyages->count(),
            'nb_reservations' => $bateau->voyages->flatMap->reservations->count(),
            'ca_total' => $bateau->voyages->flatMap->reservations->sum('prix_total') ?? 0,
        ];

        return view('personnel.gestionnaire.bateau-show', compact('bateau', 'stats'));
    }

    /**
     * Créer un bateau
     */
    public function createBateau()
    {
        return view('personnel.gestionnaire.bateau-create');
    }

    /**
     * Enregistrer un bateau
     */
    public function storeBateau(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'immatriculation' => 'required|string|max:50|unique:bateaux',
            'type' => 'required|in:cargo,mixte,passager',
            'capacite_totale' => 'required|integer|min:1',
            'capacite_passager' => 'required|integer|min:0',
            'capacite_cargaison' => 'required|integer|min:0',
            'statut' => 'required|in:en_service,en_maintenance,hors_service',
        ]);

        $bateau = Bateau::create($validated);

        return redirect()->route('gestionnaire.bateaux.show', $bateau->id)
            ->with('success', 'Bateau créé avec succès.');
    }

    /**
     * Modifier un bateau
     */
    public function editBateau($id)
    {
        $bateau = Bateau::findOrFail($id);
        return view('personnel.gestionnaire.bateau-edit', compact('bateau'));
    }

    /**
     * Mettre à jour un bateau
     */
    public function updateBateau(Request $request, $id)
    {
        $bateau = Bateau::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'immatriculation' => 'required|string|max:50|unique:bateaux,immatriculation,' . $id,
            'type' => 'required|in:cargo,mixte,passager',
            'capacite_totale' => 'required|integer|min:1',
            'capacite_passager' => 'required|integer|min:0',
            'capacite_cargaison' => 'required|integer|min:0',
            'statut' => 'required|in:en_service,en_maintenance,hors_service',
        ]);

        $bateau->update($validated);

        return redirect()->route('gestionnaire.bateaux.show', $bateau->id)
            ->with('success', 'Bateau mis à jour avec succès.');
    }

    /**
     * Supprimer un bateau
     */
    public function deleteBateau($id)
    {
        $bateau = Bateau::findOrFail($id);

        if ($bateau->voyages()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un bateau qui a des voyages.');
        }

        $bateau->delete();

        return redirect()->route('gestionnaire.bateaux')
            ->with('success', 'Bateau supprimé avec succès.');
    }

    /**
     * Gestion des ports
     */
    public function ports()
    {
        $ports = Port::withCount('quais')->orderBy('nom')->get();
        return view('personnel.gestionnaire.ports', compact('ports'));
    }

    /**
     * Gestion des quais
     */
    public function quais(Request $request)
    {
        $query = Quai::with('port');

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $quais = $query->orderBy('idport')->orderBy('numero')->get();

        return view('personnel.gestionnaire.quais', compact('quais', 'request'));
    }

    /**
     * Exporter les voyages en CSV
     */
    public function exportVoyages(Request $request)
    {
        $query = Voyage::with('bateau');

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $voyages = $query->get();

        $filename = "voyages_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Code', 'Bateau', 'Description', 'Date départ', 'Statut', 'Réservations']);

        foreach ($voyages as $v) {
            fputcsv($handle, [
                $v->code_voyage,
                $v->bateau->nom ?? 'N/A',
                $v->description ?? '',
                Carbon::parse($v->date_depart)->format('d/m/Y H:i'),
                $v->statut,
                $v->reservations->count()
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