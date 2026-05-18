<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contiendra;
use App\Models\Pavillon;
use App\Models\Trajet;
use Illuminate\Http\Request;

class ContiendraController extends Controller
{
    public function index()
    {
        $contiendras = Contiendra::with('pavillon', 'trajet')->get();
        return view('admin.contiendra.index', compact('contiendras'));
    }

    public function create()
    {
        $pavillons = Pavillon::all();
        $trajets = Trajet::all();
        return view('admin.contiendra.create', compact('pavillons', 'trajets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpavillon' => 'required|exists:pavillons,id',
            'idtrajet' => 'required|exists:trajets,id',
            'prix' => 'required|numeric|min:0'
        ]);

        Contiendra::create($request->all());

        return redirect()->route('admin.contiendra.index')
            ->with('success', 'Liaison pavillon-trajet créée avec succès.');
    }

    public function show($idpavillon, $idtrajet)
    {
        $contiendra = Contiendra::where('idpavillon', $idpavillon)
                                ->where('idtrajet', $idtrajet)
                                ->firstOrFail();
        return view('admin.contiendra.show', compact('contiendra'));
    }

    public function edit($idpavillon, $idtrajet)
    {
        $contiendra = Contiendra::where('idpavillon', $idpavillon)
                                ->where('idtrajet', $idtrajet)
                                ->firstOrFail();
        $pavillons = Pavillon::all();
        $trajets = Trajet::all();
        return view('admin.contiendra.edit', compact('contiendra', 'pavillons', 'trajets'));
    }

    public function update(Request $request, $idpavillon, $idtrajet)
    {
        $contiendra = Contiendra::where('idpavillon', $idpavillon)
                                ->where('idtrajet', $idtrajet)
                                ->firstOrFail();

        $request->validate([
            'prix' => 'required|numeric|min:0'
        ]);

        $contiendra->update(['prix' => $request->prix]);

        return redirect()->route('admin.contiendra.index')
            ->with('success', 'Liaison pavillon-trajet modifiée avec succès.');
    }

    public function destroy($idpavillon, $idtrajet)
    {
        $contiendra = Contiendra::where('idpavillon', $idpavillon)
                                ->where('idtrajet', $idtrajet)
                                ->firstOrFail();
        $contiendra->delete();

        return redirect()->route('admin.contiendra.index')
            ->with('success', 'Liaison pavillon-trajet supprimée avec succès.');
    }
}