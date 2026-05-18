<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        
        $totalReservations = Reservation::where('idclient', $client->id)->count();
        $voyagesAVenir = Reservation::where('idclient', $client->id)
            ->where('statut', 'confirme')
            ->where('date_embarquement', '>', now())
            ->count();
        $totalPaiements = Paiement::whereHas('reservation', function($q) use ($client) {
            $q->where('idclient', $client->id);
        })->count();
        
        $dernieresReservations = Reservation::where('idclient', $client->id)
            ->with('voyage')
            ->latest()
            ->take(5)
            ->get();
        
        return view('client.dashboard', compact('totalReservations', 'voyagesAVenir', 'totalPaiements', 'dernieresReservations'));
    }
}