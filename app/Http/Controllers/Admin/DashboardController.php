<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bateau;
use App\Models\Port;
use App\Models\Voyage;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Pavillon;
use App\Models\Quai;
use App\Models\Paiement;
use App\Models\Trajet;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filtre par période (mois/année)
        $mois = $request->get('mois', date('m'));
        $annee = $request->get('annee', date('Y'));

        // Statistiques générales
        $totalBateaux = Bateau::count();
        $totalPorts = Port::count();
        $totalVoyages = Voyage::count();
        $totalUsers = User::count();
        $totalQuais = Quai::count();
        $totalTrajets = Trajet::count();
        $totalPaiements = Paiement::count();
        $montantTotalPaiements = Paiement::sum('montant');

        // Réservations par mois (6 derniers mois)
        $reservationsParMois = Reservation::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois', 'DESC')
            ->limit(6)
            ->get();

        $moisLabels = $reservationsParMois->pluck('mois')->reverse()->values();
        $reservationsData = $reservationsParMois->pluck('total')->reverse()->values();

        // Recettes par pavillon
        $recettesParPavillon = Reservation::select(
                'pavillons.nom',
                DB::raw('SUM(reservations.prix_total) as total')
            )
            ->join('pavillons', 'reservations.idpavillon', '=', 'pavillons.id')
            ->whereNotNull('reservations.idpavillon')
            ->groupBy('pavillons.nom')
            ->get();

        $pavillonLabels = $recettesParPavillon->pluck('nom');
        $recettesData = $recettesParPavillon->pluck('total');

        // Occupation des bateaux
        $bateaux = Bateau::all();
        $occupationLabels = [];
        $occupationData = [];
        foreach ($bateaux as $bateau) {
            $totalPlaces = $bateau->capacite_passager;
            $reservees = Reservation::whereHas('voyage', function($q) use ($bateau) {
                $q->where('idbateau', $bateau->id);
            })->count();
            $taux = $totalPlaces > 0 ? round(($reservees / $totalPlaces) * 100) : 0;
            $occupationLabels[] = $bateau->nom;
            $occupationData[] = $taux;
        }

        // Occupation des quais
        $quais = Quai::all();
        $occupationQuaisLabels = [];
        $occupationQuaisData = [];
        foreach ($quais as $quai) {
            $occupationQuaisLabels[] = $quai->nom . ' (n°' . $quai->numero . ')';
            $taux = 0;
            if ($quai->statut == 'occupe') {
                $taux = 100;
            }
            $occupationQuaisData[] = $taux;
        }

        // Top 5 clients
        $topClients = Client::select('client.id', 'client.nom', 'client.prenom', DB::raw('SUM(reservations.prix_total) as total_depense'))
            ->join('reservations', 'client.id', '=', 'reservations.idclient')
            ->groupBy('client.id', 'client.nom', 'client.prenom')
            ->orderBy('total_depense', 'DESC')
            ->limit(5)
            ->get();

        // Réservations du jour
        $reservationsAujourdhui = Reservation::whereDate('date_embarquement', today())
            ->with('client', 'voyage')
            ->get();

        // Bateaux complets (alertes)
        $bateauxComplets = [];
        foreach ($bateaux as $bateau) {
            $totalPlaces = $bateau->capacite_passager;
            $reservees = Reservation::whereHas('voyage', function($q) use ($bateau) {
                $q->where('idbateau', $bateau->id);
            })->count();
            if ($totalPlaces > 0 && $reservees >= $totalPlaces) {
                $bateauxComplets[] = $bateau;
            }
        }

        return view('admin.dashboard', compact(
            'totalBateaux',
            'totalPorts',
            'totalVoyages',
            'totalUsers',
            'totalQuais',
            'totalTrajets',
            'totalPaiements',
            'montantTotalPaiements',
            'moisLabels',
            'reservationsData',
            'pavillonLabels',
            'recettesData',
            'occupationLabels',
            'occupationData',
            'occupationQuaisLabels',
            'occupationQuaisData',
            'topClients',
            'reservationsAujourdhui',
            'bateauxComplets'
        ));
    }
}