<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quai;
use App\Models\Port;
use Carbon\Carbon;

class QuaiController extends Controller
{
    /**
     * Liste des quais avec filtres
     */
    public function index(Request $request)
    {
        $query = Quai::with('port');

        // Filtre par statut
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        // Filtre par type
        if ($request->filled('type') && $request->type != 'tous') {
            $query->where('type_quai', $request->type);
        }

        // Filtre par port
        if ($request->filled('port_id')) {
            $query->where('idport', $request->port_id);
        }

        $quais = $query->orderBy('idport')->orderBy('numero')->paginate(15);

        // Statistiques
        $statistiques = [
            'total' => Quai::count(),
            'libre' => Quai::where('statut', 'libre')->count(),
            'occupe' => Quai::where('statut', 'occupe')->count(),
            'maintenance' => Quai::where('statut', 'maintenance')->count(),
            'passager' => Quai::where('type_quai', 'passager')->count(),
            'cargaison' => Quai::where('type_quai', 'cargaison')->count(),
            'mixte' => Quai::where('type_quai', 'mixte')->count(),
        ];

        // Liste des ports pour le filtre
        $ports = Port::where('statut', 'actif')->get();

        return view('superviseur.quais.index', compact('quais', 'statistiques', 'request', 'ports'));
    }

    /**
     * Détail d'un quai
     */
    public function show($id)
    {
        $quai = Quai::with(['port', 'bateaux'])->findOrFail($id);
        return view('superviseur.quais.show', compact('quai'));
    }

    /**
     * Mettre à jour le statut d'un quai
     */
    public function updateStatut(Request $request, $id)
    {
        $quai = Quai::findOrFail($id);
        
        $validated = $request->validate([
            'statut' => 'required|in:libre,occupe,maintenance'
        ]);

        $ancienStatut = $quai->statut;
        $quai->statut = $validated['statut'];
        $quai->save();

        return redirect()->back()->with('success', 
            "Le quai '{$quai->nom}' est passé de '{$ancienStatut}' à '{$quai->statut}' avec succès."
        );
    }

    /**
     * Afficher les bateaux assignés à un quai
     */
    public function bateaux($id)
    {
        $quai = Quai::with('bateaux')->findOrFail($id);
        return view('superviseur.quais.bateaux', compact('quai'));
    }

    /**
     * Libérer un quai (le mettre en libre)
     */
    public function liberer($id)
    {
        $quai = Quai::findOrFail($id);
        
        if ($quai->statut === 'libre') {
            return redirect()->back()->with('info', 'Ce quai est déjà libre.');
        }

        $quai->statut = 'libre';
        $quai->save();

        return redirect()->back()->with('success', "Le quai '{$quai->nom}' a été libéré avec succès.");
    }

    /**
     * Exporter les quais en CSV
     */
    public function export()
    {
        $quais = Quai::with('port')->get();

        $filename = "quais_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, ['Nom', 'Numéro', 'Port', 'Type', 'Capacité', 'Statut']);
        
        foreach ($quais as $q) {
            fputcsv($handle, [
                $q->nom,
                $q->numero,
                $q->port->nom ?? 'N/A',
                $q->type_quai,
                $q->capacite,
                $q->statut
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