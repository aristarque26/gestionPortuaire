<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bateau;
use Illuminate\Http\Request;

class BateauController extends Controller
{
    /**
     * Afficher la liste des bateaux
     */
    public function index()
    {
        $bateaux = Bateau::all();
        return view('admin.bateaux.index', compact('bateaux'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.bateaux.create');
    }

    /**
     * Enregistrer un nouveau bateau
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite_totale' => 'required|integer',
            'type' => 'required|in:cargo,mixte,passager',
            'immatriculation' => 'required|string|max:50|unique:bateaux',
            'capacite_passager' => 'required|integer',
            'capacite_cargaison' => 'required|integer',
            'statut' => 'required|in:en_service,en_maintenance,hors_service',
        ]);

        Bateau::create($request->all());

        return redirect()->route('admin.bateaux.index')
            ->with('success', 'Bateau créé avec succès.');
    }

    /**
     * Afficher les détails d'un bateau
     */
    public function show($id)
    {
        $bateau = Bateau::findOrFail($id);
        return view('admin.bateaux.show', compact('bateau'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($id)
    {
        $bateau = Bateau::findOrFail($id);
        return view('admin.bateaux.edit', compact('bateau'));
    }

    /**
     * Mettre à jour un bateau
     */
    public function update(Request $request, $id)
    {
        $bateau = Bateau::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite_totale' => 'required|integer',
            'type' => 'required|in:cargo,mixte,passager',
            'immatriculation' => 'required|string|max:50|unique:bateaux,immatriculation,' . $id,
            'capacite_passager' => 'required|integer',
            'capacite_cargaison' => 'required|integer',
            'statut' => 'required|in:en_service,en_maintenance,hors_service',
        ]);

        $bateau->update($request->all());

        return redirect()->route('admin.bateaux.index')
            ->with('success', 'Bateau modifié avec succès.');
    }

    /**
     * Supprimer un bateau
     */
    public function destroy($id)
    {
        $bateau = Bateau::findOrFail($id);
        $bateau->delete();

        return redirect()->route('admin.bateaux.index')
            ->with('success', 'Bateau supprimé avec succès.');
    }
}