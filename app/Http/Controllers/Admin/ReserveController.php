<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserve;
use App\Models\Reservation;
use App\Models\Pavillon;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index()
    {
        $reserves = Reserve::with('reservation', 'pavillon')->get();
        return view('admin.reserve.index', compact('reserves'));
    }

    public function show($idreservation, $idpavillon)
    {
        $reserve = Reserve::where('idreservation', $idreservation)
                          ->where('idpavillon', $idpavillon)
                          ->firstOrFail();
        return view('admin.reserve.show', compact('reserve'));
    }

    public function edit($idreservation, $idpavillon)
    {
        $reserve = Reserve::where('idreservation', $idreservation)
                          ->where('idpavillon', $idpavillon)
                          ->firstOrFail();
        $reservations = Reservation::all();
        $pavillons = Pavillon::all();
        return view('admin.reserve.edit', compact('reserve', 'reservations', 'pavillons'));
    }

    public function update(Request $request, $idreservation, $idpavillon)
    {
        $reserve = Reserve::where('idreservation', $idreservation)
                          ->where('idpavillon', $idpavillon)
                          ->firstOrFail();

        $request->validate([
            'prix' => 'required|numeric|min:0'
        ]);

        $reserve->update(['prix' => $request->prix]);

        return redirect()->route('admin.reserve.index')
            ->with('success', 'Liaison réservation-pavillon modifiée avec succès.');
    }

    public function destroy($idreservation, $idpavillon)
    {
        $reserve = Reserve::where('idreservation', $idreservation)
                          ->where('idpavillon', $idpavillon)
                          ->firstOrFail();
        $reserve->delete();

        return redirect()->route('admin.reserve.index')
            ->with('success', 'Liaison réservation-pavillon supprimée avec succès.');
    }
}