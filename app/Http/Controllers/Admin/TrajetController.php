<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trajet;
use App\Models\Voyage;
use Illuminate\Http\Request;

class TrajetController extends Controller
{
    public function index()
    {
        $trajets = Trajet::with('voyage')->get();
        return view('admin.trajets.index', compact('trajets'));
    }

    public function create()
    {
        $voyages = Voyage::all();
        return view('admin.trajets.create', compact('voyages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'distance' => 'required|numeric',
            'ordre' => 'required|integer',
            'idvoyage' => 'required|exists:voyages,id'
        ]);

        Trajet::create($request->all());

        return redirect()->route('admin.trajets.index')
            ->with('success', 'Trajet créé avec succès.');
    }

    public function show($id)
    {
        $trajet = Trajet::with('voyage')->findOrFail($id);
        return view('admin.trajets.show', compact('trajet'));
    }

    public function edit($id)
    {
        $trajet = Trajet::findOrFail($id);
        $voyages = Voyage::all();
        return view('admin.trajets.edit', compact('trajet', 'voyages'));
    }

    public function update(Request $request, $id)
    {
        $trajet = Trajet::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'distance' => 'required|numeric',
            'ordre' => 'required|integer',
            'idvoyage' => 'required|exists:voyages,id'
        ]);

        $trajet->update($request->all());

        return redirect()->route('admin.trajets.index')
            ->with('success', 'Trajet modifié avec succès.');
    }

    public function destroy($id)
    {
        $trajet = Trajet::findOrFail($id);
        $trajet->delete();

        return redirect()->route('admin.trajets.index')
            ->with('success', 'Trajet supprimé avec succès.');
    }
}