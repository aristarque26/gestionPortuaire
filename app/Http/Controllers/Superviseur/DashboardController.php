<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Personnel;
use App\Models\Quai;
use App\Models\Paiement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du superviseur
     */
    public function index()
    {
        $user = Auth::user();

        // ============================================
        // STATISTIQUES GLOBALES (Cartes)
        // ============================================
        
        // Réservations
        $stats = [
            'reservations_aujourdhui' => Reservation::whereDate('created_at', Carbon::today())->count(),
            'reservations_en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'reservations_confirmees' => Reservation::where('statut', 'confirme')->count(),
            'reservations_payees' => Reservation::where('statut', 'paye')->count(),
            'reservations_annulees' => Reservation::where('statut', 'annule')->count(),
            'reservations_arrivees' => Reservation::where('statut', 'arrive')->count(),
            'total_reservations' => Reservation::count(),
        ];

        // Voyages
        $stats['voyages_prevus'] = Voyage::where('statut', 'prevu')->count();
        $stats['voyages_en_cours'] = Voyage::where('statut', 'en_cours')->count();
        $stats['voyages_termines'] = Voyage::where('statut', 'termine')->count();
        $stats['voyages_annules'] = Voyage::where('statut', 'annule')->count();
        $stats['total_voyages'] = Voyage::count();

        // Bateaux
        $stats['bateaux_service'] = Bateau::where('statut', 'en_service')->count();
        $stats['bateaux_maintenance'] = Bateau::where('statut', 'en_maintenance')->count();
        $stats['bateaux_hors_service'] = Bateau::where('statut', 'hors_service')->count();
        $stats['total_bateaux'] = Bateau::count();

        // Personnel
        $stats['personnel_actif'] = Personnel::where('statut', 'actif')->count();
        $stats['personnel_inactif'] = Personnel::where('statut', 'inactif')->count();
        $stats['total_personnel'] = Personnel::count();

        // Quais
        $stats['quais_libres'] = Quai::where('statut', 'libre')->count();
        $stats['quais_occupes'] = Quai::where('statut', 'occupe')->count();
        $stats['quais_maintenance'] = Quai::where('statut', 'maintenance')->count();
        $stats['total_quais'] = Quai::count();

        // ============================================
        // STATISTIQUES FINANCIERES
        // ============================================
        
        // CA par période
        $stats['ca_aujourdhui'] = Paiement::whereDate('created_at', Carbon::today())
            ->where('statut', 'paye')
            ->sum('montant') ?? 0;
            
        $stats['ca_semaine'] = Paiement::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->where('statut', 'paye')
            ->sum('montant') ?? 0;
            
        $stats['ca_mois'] = Paiement::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('statut', 'paye')
            ->sum('montant') ?? 0;
            
        $stats['ca_total'] = Paiement::where('statut', 'paye')->sum('montant') ?? 0;

        // Nombre de paiements
        $stats['paiements_aujourdhui'] = Paiement::whereDate('created_at', Carbon::today())->count();
        $stats['paiements_attente'] = Paiement::where('statut', 'en_attente')->count();
        $stats['paiements_echoues'] = Paiement::where('statut', 'echoue')->count();

        // ============================================
        // DONNEES POUR LES GRAPHIQUES
        // ============================================
        
        // Réservations des 7 derniers jours
        $chartReservations = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartReservations['labels'][] = $date->format('d/m');
            $chartReservations['data'][] = Reservation::whereDate('created_at', $date)->count();
        }

        // Répartition des réservations par statut
        $chartStatuts = [
            'labels' => ['En attente', 'Confirmées', 'Payées', 'Arrivées', 'Annulées'],
            'data' => [
                Reservation::where('statut', 'en_attente')->count(),
                Reservation::where('statut', 'confirme')->count(),
                Reservation::where('statut', 'paye')->count(),
                Reservation::where('statut', 'arrive')->count(),
                Reservation::where('statut', 'annule')->count(),
            ]
        ];

        // CA par bateau (top 5)
        $chartCABateau = [];
        $bateaux = Bateau::with(['voyages.reservations.paiements' => function($q) {
            $q->where('statut', 'paye');
        }])->take(5)->get();
        
        foreach ($bateaux as $bateau) {
            $total = 0;
            foreach ($bateau->voyages as $voyage) {
                foreach ($voyage->reservations as $reservation) {
                    foreach ($reservation->paiements as $paiement) {
                        $total += $paiement->montant;
                    }
                }
            }
            if ($total > 0) {
                $chartCABateau['labels'][] = $bateau->nom;
                $chartCABateau['data'][] = $total;
            }
        }

        // ============================================
        // ALERTES
        // ============================================
        
        $alertes = [];
        
        // Quais en maintenance
        $quaisMaintenance = Quai::where('statut', 'maintenance')->get();
        foreach ($quaisMaintenance as $quai) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "Le quai '{$quai->nom}' est en maintenance depuis le " . $quai->updated_at->format('d/m/Y'),
                'lien' => route('superviseur.quais.index')
            ];
        }

        // Réservations en attente depuis +48h
        $reservationsEnAttente = Reservation::where('statut', 'en_attente')
            ->where('created_at', '<', Carbon::now()->subHours(48))
            ->count();
        if ($reservationsEnAttente > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$reservationsEnAttente} réservation(s) en attente depuis plus de 48h",
                'lien' => route('superviseur.reservations.index', ['statut' => 'en_attente'])
            ];
        }

        // Paiements échoués
        $paiementsEchoues = Paiement::where('statut', 'echoue')->count();
        if ($paiementsEchoues > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$paiementsEchoues} paiement(s) échoué(s) à traiter",
                'lien' => route('superviseur.statistiques.financieres')
            ];
        }

        // Bateaux en maintenance
        $bateauxMaintenance = Bateau::where('statut', 'en_maintenance')->count();
        if ($bateauxMaintenance > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$bateauxMaintenance} bateau(x) en maintenance",
                'lien' => route('superviseur.bateaux.index')
            ];
        }

        // ============================================
        // DERNIERES ACTIVITES
        // ============================================
        
        $dernieresReservations = Reservation::with(['client', 'voyage.bateau'])
            ->latest()
            ->take(10)
            ->get();

        $derniersPaiements = Paiement::with(['reservation.client'])
            ->latest()
            ->take(5)
            ->get();

        $derniersPersonnel = Personnel::with('user')
            ->latest()
            ->take(5)
            ->get();

        // ============================================
        // VUE
        // ============================================
        
        return view('superviseur.dashboard.index', compact(
            'user',
            'stats',
            'chartReservations',
            'chartStatuts',
            'chartCABateau',
            'alertes',
            'dernieresReservations',
            'derniersPaiements',
            'derniersPersonnel'
        ));
    }
}