<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with('reservation')->get();
        return view('admin.paiements.index', compact('paiements'));
    }

    public function show($id)
    {
        $paiement = Paiement::with('reservation')->findOrFail($id);
        return view('admin.paiements.show', compact('paiement'));
    }

    public function update(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:paye,en_attente,echoue,rembourse'
        ]);

        $paiement->update(['statut' => $request->statut]);

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Statut du paiement mis à jour.');
    }

    public function destroy($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->delete();

        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}