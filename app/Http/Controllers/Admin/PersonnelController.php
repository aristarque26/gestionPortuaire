<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Afficher la liste des agents
     */
    public function index()
    {
        $personnels = Personnel::with('user')->paginate(10);
        return view('admin.personnel.index', compact('personnels'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.personnel.create');
    }

    /**
     * Enregistrer un nouvel agent
     */
    public function store(Request $request)
    {
        $request->validate([
            'matricule' => 'required|unique:personnel,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'nationalite' => 'required|string|max:50',
            'genre' => 'required|in:Homme,Femme',
            'poste' => 'required|string|max:100',
            'service' => 'required|string|max:100',
            'date_embauche' => 'required|date',
            'personnel_role' => 'required|in:superviseur,comptable,caissier,agent_portuaire,gestionnaire',
            'salaire' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif', // ✅ AJOUTÉ
        ]);

        // 1. Créer l'utilisateur
        $user = User::create([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->password),
            'role' => 'personnel',
            'statut' => $request->statut,
        ]);

        // 2. Créer le personnel lié
        Personnel::create([
            'user_id' => $user->id,
            'matricule' => $request->matricule,
            'poste' => $request->poste,
            'service' => $request->service,
            'date_embauche' => $request->date_embauche,
            'personnel_role' => $request->personnel_role,
            'salaire' => $request->salaire,
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Agent ajouté avec succès.');
    }

    /**
     * Afficher les détails d'un agent
     */
    public function show($id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);
        return view('admin.personnel.show', compact('personnel'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);
        return view('admin.personnel.edit', compact('personnel'));
    }

    /**
     * Mettre à jour un agent
     */
    public function update(Request $request, $id)
    {
        $personnel = Personnel::findOrFail($id);
        $user = $personnel->user;

        $request->validate([
            'matricule' => 'required|unique:personnel,matricule,' . $id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'nationalite' => 'required|string|max:50',
            'genre' => 'required|in:Homme,Femme',
            'poste' => 'required|string|max:100',
            'service' => 'required|string|max:100',
            'date_embauche' => 'required|date',
            'personnel_role' => 'required|in:superviseur,comptable,caissier,agent_portuaire,gestionnaire',
            'salaire' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif', // ✅ AJOUTÉ
        ]);

        // 1. Mettre à jour l'utilisateur
        $user->update([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'statut' => $request->statut,
        ]);

        // 2. Mettre à jour le personnel
        $personnel->update([
            'matricule' => $request->matricule,
            'poste' => $request->poste,
            'service' => $request->service,
            'date_embauche' => $request->date_embauche,
            'personnel_role' => $request->personnel_role,
            'salaire' => $request->salaire,
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Agent modifié avec succès.');
    }

    /**
     * Supprimer un agent
     */
    public function destroy($id)
    {
        $personnel = Personnel::findOrFail($id);
        $user = $personnel->user;

        $personnel->delete();
        $user->delete();

        return redirect()->route('admin.personnel.index')
            ->with('success', 'Agent supprimé avec succès.');
    }
}