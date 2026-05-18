<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use App\Models\Bateau;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index()
    {
        $voyages = Voyage::with('bateau')->get();
        return view('admin.voyages.index', compact('voyages'));
    }

    public function create()
    {
        $bateaux = Bateau::where('statut', 'en_service')->get();
        return view('admin.voyages.create', compact('bateaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_voyage' => 'required|string|max:50|unique:voyages',
            'description' => 'nullable|string',
            'statut' => 'required|in:prevu,en_cours,termine,annule',
            'date_depart' => 'required|date',
            'idbateau' => 'required|exists:bateaux,id'
        ]);

        Voyage::create($request->all());

        return redirect()->route('admin.voyages.index')
            ->with('success', 'Voyage créé avec succès.');
    }

    public function show($id)
    {
        $voyage = Voyage::with('bateau', 'reservations')->findOrFail($id);
        return view('admin.voyages.show', compact('voyage'));
    }

    public function edit($id)
    {
        $voyage = Voyage::findOrFail($id);
        $bateaux = Bateau::where('statut', 'en_service')->get();
        return view('admin.voyages.edit', compact('voyage', 'bateaux'));
    }

    public function update(Request $request, $id)
    {
        $voyage = Voyage::findOrFail($id);

        $request->validate([
            'code_voyage' => 'required|string|max:50|unique:voyages,code_voyage,' . $id,
            'description' => 'nullable|string',
            'statut' => 'required|in:prevu,en_cours,termine,annule',
            'date_depart' => 'required|date',
            'idbateau' => 'required|exists:bateaux,id'
        ]);

        $voyage->update($request->all());

        return redirect()->route('admin.voyages.index')
            ->with('success', 'Voyage modifié avec succès.');
    }

    public function destroy($id)
    {
        $voyage = Voyage::findOrFail($id);
        $voyage->delete();

        return redirect()->route('admin.voyages.index')
            ->with('success', 'Voyage supprimé avec succès.');
    }
}