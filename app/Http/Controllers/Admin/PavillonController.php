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
            'capacite_max' => 'required|integer|min:1',
            'classe' => 'required|string|max:255',
            'unite' => 'required|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'prix_tonne' => 'nullable|numeric|min:0',
            'devise' => 'required|in:FC,USD',
            'idbateau' => 'required|exists:bateaux,id',
        ]);

        $bateau = Bateau::findOrFail($request->idbateau);
        $capacite = $request->capacite_max;

        // 🔥 VÉRIFICATION PASSAGER (si prix_unitaire > 0)
        if ($request->prix_unitaire > 0) {
            $capacitePassagerExistante = Pavillon::where('idbateau', $bateau->id)
                ->where('prix_unitaire', '>', 0)
                ->sum('capacite_max');

            $totalPassager = $capacitePassagerExistante + $capacite;

            if ($totalPassager > $bateau->capacite_passager) {
                return back()
                    ->withInput()
                    ->with('error', '❌ La capacité totale des pavillons passagers (' . $totalPassager . ') dépasse la capacité maximale du bateau pour les passagers (' . $bateau->capacite_passager . ' places).');
            }
        }

        // 🔥 VÉRIFICATION CARGAISON (si prix_tonne > 0)
        if ($request->prix_tonne > 0) {
            $capaciteCargaisonExistante = Pavillon::where('idbateau', $bateau->id)
                ->where('prix_tonne', '>', 0)
                ->sum('capacite_max');

            $totalCargaison = $capaciteCargaisonExistante + $capacite;

            if ($totalCargaison > $bateau->capacite_cargaison) {
                return back()
                    ->withInput()
                    ->with('error', '❌ La capacité totale des pavillons cargaison (' . $totalCargaison . ') dépasse la capacité maximale du bateau pour la cargaison (' . $bateau->capacite_cargaison . ' tonnes).');
            }
        }

        // Enregistrement
        Pavillon::create($request->all());

        return redirect()->route('admin.pavillons.index')
            ->with('success', 'Pavillon ajouté avec succès.');
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
            'capacite_max' => 'required|integer|min:1',
            'classe' => 'required|string|max:255',
            'unite' => 'required|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'prix_tonne' => 'nullable|numeric|min:0',
            'devise' => 'required|in:FC,USD',
            'idbateau' => 'required|exists:bateaux,id',
        ]);

        $bateau = Bateau::findOrFail($request->idbateau);
        $capacite = $request->capacite_max;

        // 🔥 VÉRIFICATION PASSAGER (modification)
        if ($request->prix_unitaire > 0) {
            $capacitePassagerExistante = Pavillon::where('idbateau', $bateau->id)
                ->where('prix_unitaire', '>', 0)
                ->where('id', '!=', $id)
                ->sum('capacite_max');

            $totalPassager = $capacitePassagerExistante + $capacite;

            if ($totalPassager > $bateau->capacite_passager) {
                return back()
                    ->withInput()
                    ->with('error', '❌ La capacité totale des pavillons passagers (' . $totalPassager . ') dépasse la capacité maximale du bateau pour les passagers (' . $bateau->capacite_passager . ' places).');
            }
        }

        // 🔥 VÉRIFICATION CARGAISON (modification)
        if ($request->prix_tonne > 0) {
            $capaciteCargaisonExistante = Pavillon::where('idbateau', $bateau->id)
                ->where('prix_tonne', '>', 0)
                ->where('id', '!=', $id)
                ->sum('capacite_max');

            $totalCargaison = $capaciteCargaisonExistante + $capacite;

            if ($totalCargaison > $bateau->capacite_cargaison) {
                return back()
                    ->withInput()
                    ->with('error', '❌ La capacité totale des pavillons cargaison (' . $totalCargaison . ') dépasse la capacité maximale du bateau pour la cargaison (' . $bateau->capacite_cargaison . ' tonnes).');
            }
        }

        // Mise à jour
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