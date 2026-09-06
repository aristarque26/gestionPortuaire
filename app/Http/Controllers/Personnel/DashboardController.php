<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Personnel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord générique du personnel (fallback)
     */
    public function index()
    {
        $user = Auth::user();
        $user->load('personnel');

        // Statistiques de base
        $stats = [
            'reservations_aujourdhui' => Reservation::whereDate('created_at', Carbon::today())->count(),
            'voyages_en_cours' => Voyage::where('statut', 'en_cours')->count(),
            'bateaux_service' => Bateau::where('statut', 'en_service')->count(),
            'personnel_actif' => Personnel::where('statut', 'actif')->count(),
        ];

        // Dernières réservations
        $dernieresReservations = Reservation::with(['client', 'voyage.bateau'])
            ->latest()
            ->take(10)
            ->get();

        return view('personnel.dashboard', compact('user', 'stats', 'dernieresReservations'));
    }
}