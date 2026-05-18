<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appartenir;
use App\Models\Bateau;
use App\Models\Quai;
use Illuminate\Http\Request;

class AppartenirController extends Controller
{
    public function index()
    {
        $appartenirs = Appartenir::with('bateau', 'quai')->get();
        return view('admin.appartenir.index', compact('appartenirs'));
    }

    public function create()
    {
        $bateaux = Bateau::all();
        $quais = Quai::all();
        return view('admin.appartenir.create', compact('bateaux', 'quais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idbateau' => 'required|exists:bateaux,id',
            'idquai' => 'required|exists:quais,id'
        ]);

        Appartenir::create($request->all());

        return redirect()->route('admin.appartenir.index')
            ->with('success', 'Liaison bateau-quai créée avec succès.');
    }

    public function show($idbateau, $idquai)
    {
        $appartenir = Appartenir::where('idbateau', $idbateau)
                                ->where('idquai', $idquai)
                                ->firstOrFail();
        return view('admin.appartenir.show', compact('appartenir'));
    }

    public function edit($idbateau, $idquai)
    {
        $appartenir = Appartenir::where('idbateau', $idbateau)
                                ->where('idquai', $idquai)
                                ->firstOrFail();
        $bateaux = Bateau::all();
        $quais = Quai::all();
        return view('admin.appartenir.edit', compact('appartenir', 'bateaux', 'quais'));
    }

    public function update(Request $request, $idbateau, $idquai)
    {
        $appartenir = Appartenir::where('idbateau', $idbateau)
                                ->where('idquai', $idquai)
                                ->firstOrFail();

        $request->validate([
            'idbateau' => 'required|exists:bateaux,id',
            'idquai' => 'required|exists:quais,id'
        ]);

        $appartenir->update($request->all());

        return redirect()->route('admin.appartenir.index')
            ->with('success', 'Liaison bateau-quai modifiée avec succès.');
    }

    public function destroy($idbateau, $idquai)
    {
        $appartenir = Appartenir::where('idbateau', $idbateau)
                                ->where('idquai', $idquai)
                                ->firstOrFail();
        $appartenir->delete();

        return redirect()->route('admin.appartenir.index')
            ->with('success', 'Liaison bateau-quai supprimée avec succès.');
    }
}