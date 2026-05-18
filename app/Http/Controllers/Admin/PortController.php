<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
    /**
     * Afficher la liste des ports
     */
    public function index()
    {
        $ports = Port::all();
        return view('admin.ports.index', compact('ports'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.ports.create');
    }

    /**
     * Enregistrer un nouveau port
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'localisation' => 'required|string',
            'ville' => 'required|string|max:255',
            'statut' => 'required|in:actif,hors_service',
        ]);

        Port::create($request->all());

        return redirect()->route('admin.ports.index')
            ->with('success', 'Port créé avec succès.');
    }

    /**
     * Afficher les détails d'un port
     */
    public function show($id)
    {
        $port = Port::findOrFail($id);
        return view('admin.ports.show', compact('port'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($id)
    {
        $port = Port::findOrFail($id);
        return view('admin.ports.edit', compact('port'));
    }

    /**
     * Mettre à jour un port
     */
    public function update(Request $request, $id)
    {
        $port = Port::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'localisation' => 'required|string',
            'ville' => 'required|string|max:255',
            'statut' => 'required|in:actif,hors_service',
        ]);

        $port->update($request->all());

        return redirect()->route('admin.ports.index')
            ->with('success', 'Port modifié avec succès.');
    }

    /**
     * Supprimer un port
     */
    public function destroy($id)
    {
        $port = Port::findOrFail($id);
        $port->delete();

        return redirect()->route('admin.ports.index')
            ->with('success', 'Port supprimé avec succès.');
    }
}