<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pavillon;
use App\Models\Bateau;
use Illuminate\Http\Request;

class PavillonController extends Controller
{
    public function index()
    {
        $pavillons = Pavillon::with('bateau')->get();
        return view('admin.pavillons.index', compact('pavillons'));
    }

    public function create()
    {
        $bateaux = Bateau::all();
        return view('admin.pavillons.create', compact('bateaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite_max' => 'required|integer',
            'classe' => 'required|string|max:255',
            'unite' => 'required|string|max:255',
            'prix_unitaire' => 'required|numeric|min:0',  // ✅ AJOUTÉ
            'idbateau' => 'required|exists:bateaux,id'
        ]);

        Pavillon::create($request->all());

        return redirect()->route('admin.pavillons.index')
            ->with('success', 'Pavillon créé avec succès.');
    }

    public function show($id)
    {
        $pavillon = Pavillon::with('bateau')->findOrFail($id);
        return view('admin.pavillons.show', compact('pavillon'));
    }

    public function edit($id)
    {
        $pavillon = Pavillon::findOrFail($id);
        $bateaux = Bateau::all();
        return view('admin.pavillons.edit', compact('pavillon', 'bateaux'));
    }

    public function update(Request $request, $id)
    {
        $pavillon = Pavillon::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite_max' => 'required|integer',
            'classe' => 'required|string|max:255',
            'unite' => 'required|string|max:255',
            'prix_unitaire' => 'required|numeric|min:0',  // ✅ AJOUTÉ
            'idbateau' => 'required|exists:bateaux,id'
        ]);

        $pavillon->update($request->all());

        return redirect()->route('admin.pavillons.index')
            ->with('success', 'Pavillon modifié avec succès.');
    }

    public function destroy($id)
    {
        $pavillon = Pavillon::findOrFail($id);
        $pavillon->delete();

        return redirect()->route('admin.pavillons.index')
            ->with('success', 'Pavillon supprimé avec succès.');
    }
}