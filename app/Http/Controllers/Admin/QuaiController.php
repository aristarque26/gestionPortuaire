<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quai;
use App\Models\Port;
use Illuminate\Http\Request;

class QuaiController extends Controller
{
    public function index()
    {
        $quais = Quai::with('port')->get();
        return view('admin.quais.index', compact('quais'));
    }

    public function create()
    {
        $ports = Port::all();
        return view('admin.quais.create', compact('ports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer',
            'type_quai' => 'required|in:passager,cargaison,mixte',
            'statut' => 'required|in:libre,occupe,maintenance',
            'numero' => 'required|integer',
            'idport' => 'required|exists:ports,id'
        ]);

        Quai::create($request->all());

        return redirect()->route('admin.quais.index')
            ->with('success', 'Quai créé avec succès.');
    }

    public function show($id)
    {
        $quai = Quai::with('port')->findOrFail($id);
        return view('admin.quais.show', compact('quai'));
    }

    public function edit($id)
    {
        $quai = Quai::findOrFail($id);
        $ports = Port::all();
        return view('admin.quais.edit', compact('quai', 'ports'));
    }

    public function update(Request $request, $id)
    {
        $quai = Quai::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer',
            'type_quai' => 'required|in:passager,cargaison,mixte',
            'statut' => 'required|in:libre,occupe,maintenance',
            'numero' => 'required|integer',
            'idport' => 'required|exists:ports,id'
        ]);

        $quai->update($request->all());

        return redirect()->route('admin.quais.index')
            ->with('success', 'Quai modifié avec succès.');
    }

    public function destroy($id)
    {
        $quai = Quai::findOrFail($id);
        $quai->delete();

        return redirect()->route('admin.quais.index')
            ->with('success', 'Quai supprimé avec succès.');
    }
}