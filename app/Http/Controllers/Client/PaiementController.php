<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        $paiements = Paiement::whereHas('reservation', function($q) use ($client) {
            $q->where('idclient', $client->id);
        })->with('reservation')->latest()->paginate(10); // ✅ paginate(10) au lieu de get()
        
        return view('client.paiements.index', compact('paiements'));
    }

    public function show($id)
    {
        $client = Auth::user()->client;
        $paiement = Paiement::whereHas('reservation', function($q) use ($client) {
            $q->where('idclient', $client->id);
        })->with('reservation')->findOrFail($id);
        
        return view('client.paiements.show', compact('paiement'));
    }
}