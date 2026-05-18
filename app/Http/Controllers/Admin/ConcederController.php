<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conceder;
use App\Models\Port;
use App\Models\Trajet;
use Illuminate\Http\Request;

class ConcederController extends Controller
{
    public function index()
    {
        $conceders = Conceder::with('port', 'trajet')->get();
        return view('admin.conceder.index', compact('conceders'));
    }

    public function create()
    {
        $ports = Port::all();
        $trajets = Trajet::all();
        return view('admin.conceder.create', compact('ports', 'trajets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idport' => 'required|exists:ports,id',
            'idtrajet' => 'required|exists:trajets,id',
            'ordre_etape' => 'required|integer',
            'role_port' => 'required|string|max:255'
        ]);

        Conceder::create($request->all());

        return redirect()->route('admin.conceder.index')
            ->with('success', 'Liaison port-trajet créée avec succès.');
    }

    public function show($idport, $idtrajet)
    {
        $conceder = Conceder::where('idport', $idport)
                            ->where('idtrajet', $idtrajet)
                            ->firstOrFail();
        return view('admin.conceder.show', compact('conceder'));
    }

    public function edit($idport, $idtrajet)
    {
        $conceder = Conceder::where('idport', $idport)
                            ->where('idtrajet', $idtrajet)
                            ->firstOrFail();
        $ports = Port::all();
        $trajets = Trajet::all();
        return view('admin.conceder.edit', compact('conceder', 'ports', 'trajets'));
    }

    public function update(Request $request, $idport, $idtrajet)
    {
        $conceder = Conceder::where('idport', $idport)
                            ->where('idtrajet', $idtrajet)
                            ->firstOrFail();

        $request->validate([
            'ordre_etape' => 'required|integer',
            'role_port' => 'required|string|max:255'
        ]);

        $conceder->update($request->only(['ordre_etape', 'role_port']));

        return redirect()->route('admin.conceder.index')
            ->with('success', 'Liaison port-trajet modifiée avec succès.');
    }

    public function destroy($idport, $idtrajet)
    {
        $conceder = Conceder::where('idport', $idport)
                            ->where('idtrajet', $idtrajet)
                            ->firstOrFail();
        $conceder->delete();

        return redirect()->route('admin.conceder.index')
            ->with('success', 'Liaison port-trajet supprimée avec succès.');
    }
}