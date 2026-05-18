<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index()
    {
        $voyages = Voyage::where('statut', 'prevu')
            ->where('date_depart', '>', now())
            ->with('bateau')
            ->orderBy('date_depart', 'asc')
            ->get();
        
        return view('client.voyages.index', compact('voyages'));
    }
}