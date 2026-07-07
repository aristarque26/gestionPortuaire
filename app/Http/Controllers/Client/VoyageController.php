<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use App\Models\Port; // ✅ AJOUT : import du modèle Port
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index()
    {
        // Récupération des voyages
        $voyages = Voyage::where('statut', 'prevu')
            ->where('date_depart', '>', now())
            ->with('bateau')
            ->orderBy('date_depart', 'asc')
            ->get();

        // 
        $ports = Port::all(); // ou Port::where('statut', 'actif')->get();

        //
        return view('client.voyages.index', compact('voyages', 'ports'));
    }
}