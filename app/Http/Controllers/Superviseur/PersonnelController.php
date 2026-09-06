<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\User;
use Carbon\Carbon;

class PersonnelController extends Controller
{
    /**
     * Liste du personnel avec filtres
     */
    public function index(Request $request)
    {
        $query = Personnel::with('user');

        // Filtre par statut
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        // Filtre par rôle
        if ($request->filled('role') && $request->role != 'tous') {
            $query->where('personnel_role', $request->role);
        }

        // Filtre par service
        if ($request->filled('service')) {
            $query->where('service', 'LIKE', "%{$request->service}%");
        }

        // Filtre par mot-clé (matricule, nom, prénom)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('matricule', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $personnel = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques globales
        $statistiques = [
            'total' => Personnel::count(),
            'actif' => Personnel::where('statut', 'actif')->count(),
            'inactif' => Personnel::where('statut', 'inactif')->count(),
            'superviseurs' => Personnel::where('personnel_role', 'superviseur')->count(),
            'comptables' => Personnel::where('personnel_role', 'comptable')->count(),
            'caissiers' => Personnel::where('personnel_role', 'caissier')->count(),
            'agents' => Personnel::where('personnel_role', 'agent_portuaire')->count(),
            'gestionnaires' => Personnel::where('personnel_role', 'gestionnaire')->count(),
        ];

        // Liste des rôles pour le filtre
        $roles = [
            'superviseur' => 'Superviseur',
            'comptable' => 'Comptable',
            'caissier' => 'Caissier',
            'agent_portuaire' => 'Agent portuaire',
            'gestionnaire' => 'Gestionnaire'
        ];

        return view('superviseur.personnel.index', compact('personnel', 'statistiques', 'request', 'roles'));
    }

    /**
     * Détail d'un personnel
     */
    public function show($id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);
        
        // Calculer l'ancienneté
        $anciennete = Carbon::parse($personnel->date_embauche)->diff(Carbon::now());

        return view('superviseur.personnel.show', compact('personnel', 'anciennete'));
    }

    /**
     * Activer un personnel
     */
    public function activer($id)
    {
        $personnel = Personnel::findOrFail($id);
        
        if ($personnel->statut === 'actif') {
            return redirect()->back()->with('info', 'Ce personnel est déjà actif.');
        }

        // Activer le personnel
        $personnel->statut = 'actif';
        $personnel->save();

        // Activer l'utilisateur associé
        if ($personnel->user) {
            $personnel->user->statut = 'actif';
            $personnel->user->save();
        }

        return redirect()->back()->with('success', "Le personnel '{$personnel->user->name} {$personnel->user->prenom}' a été activé avec succès.");
    }

    /**
     * Désactiver un personnel
     */
    public function desactiver($id)
    {
        $personnel = Personnel::findOrFail($id);
        
        if ($personnel->statut === 'inactif') {
            return redirect()->back()->with('info', 'Ce personnel est déjà inactif.');
        }

        // Désactiver le personnel
        $personnel->statut = 'inactif';
        $personnel->save();

        // Désactiver l'utilisateur associé
        if ($personnel->user) {
            $personnel->user->statut = 'inactif';
            $personnel->user->save();
        }

        return redirect()->back()->with('success', "Le personnel '{$personnel->user->name} {$personnel->user->prenom}' a été désactivé avec succès.");
    }

    /**
     * Modifier un personnel
     */
    public function edit($id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);
        $roles = [
            'superviseur' => 'Superviseur',
            'comptable' => 'Comptable',
            'caissier' => 'Caissier',
            'agent_portuaire' => 'Agent portuaire',
            'gestionnaire' => 'Gestionnaire'
        ];
        
        return view('superviseur.personnel.edit', compact('personnel', 'roles'));
    }

    /**
     * Mettre à jour un personnel
     */
    public function update(Request $request, $id)
    {
        $personnel = Personnel::findOrFail($id);
        
        $validated = $request->validate([
            'poste' => 'required|string|max:100',
            'service' => 'required|string|max:100',
            'personnel_role' => 'required|in:superviseur,comptable,caissier,agent_portuaire,gestionnaire',
            'salaire' => 'required|numeric|min:0',
            'date_embauche' => 'required|date',
        ]);

        $personnel->update($validated);

        return redirect()->route('superviseur.personnel.show', $personnel->id)
            ->with('success', 'Personnel mis à jour avec succès.');
    }

    /**
     * Exporter le personnel en CSV
     */
    public function export(Request $request)
    {
        $query = Personnel::with('user');
        
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('role') && $request->role != 'tous') {
            $query->where('personnel_role', $request->role);
        }

        $personnel = $query->get();

        $filename = "personnel_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, [
            'Matricule', 'Nom', 'Prénom', 'Email', 'Téléphone',
            'Poste', 'Service', 'Rôle', 'Salaire', 'Date embauche', 'Statut'
        ]);
        
        foreach ($personnel as $p) {
            fputcsv($handle, [
                $p->matricule,
                $p->user->name ?? 'N/A',
                $p->user->prenom ?? 'N/A',
                $p->user->email ?? 'N/A',
                $p->user->telephone ?? 'N/A',
                $p->poste,
                $p->service,
                $p->personnel_role,
                number_format($p->salaire, 2, ',', ' '),
                Carbon::parse($p->date_embauche)->format('d/m/Y'),
                $p->statut
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