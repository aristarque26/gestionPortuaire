<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Paiement;
use App\Models\Voyage;
use App\Models\Bateau;
use App\Models\Personnel;
use App\Models\Client;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    /**
     * Page principale des statistiques
     */
    public function index()
    {
        return view('superviseur.statistiques.index');
    }

    /**
     * Statistiques financières
     */
    public function financieres(Request $request)
    {
        // Période
        $periode = $request->periode ?? 'mois';
        $dateDebut = Carbon::now();
        $dateFin = Carbon::now();

        switch ($periode) {
            case 'aujourdhui':
                $dateDebut = Carbon::today();
                $dateFin = Carbon::today();
                break;
            case 'semaine':
                $dateDebut = Carbon::now()->startOfWeek();
                $dateFin = Carbon::now()->endOfWeek();
                break;
            case 'mois':
                $dateDebut = Carbon::now()->startOfMonth();
                $dateFin = Carbon::now()->endOfMonth();
                break;
            case 'trimestre':
                $dateDebut = Carbon::now()->startOfQuarter();
                $dateFin = Carbon::now()->endOfQuarter();
                break;
            case 'annee':
                $dateDebut = Carbon::now()->startOfYear();
                $dateFin = Carbon::now()->endOfYear();
                break;
            default:
                $dateDebut = Carbon::now()->startOfMonth();
                $dateFin = Carbon::now()->endOfMonth();
        }

        // Paiements
        $paiements = Paiement::where('statut', 'paye')
            ->whereBetween('created_at', [$dateDebut, $dateFin]);

        // Statistiques financières
        $stats = [
            'ca_total' => $paiements->sum('montant') ?? 0,
            'nb_paiements' => $paiements->count(),
            'ca_moyen' => $paiements->avg('montant') ?? 0,
            'ca_min' => $paiements->min('montant') ?? 0,
            'ca_max' => $paiements->max('montant') ?? 0,
        ];

        // Paiements par mode
        $paiementsParMode = Paiement::where('statut', 'paye')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->selectRaw('mode_paiement, COUNT(*) as total, SUM(montant) as montant')
            ->groupBy('mode_paiement')
            ->get();

        // CA par jour (pour le graphique) - AVEC GESTION DES DONNÉES VIDES
        $chartCAJournalier = [
            'labels' => [],
            'data' => []
        ];

        $jours = $dateFin->diffInDays($dateDebut) + 1;

        for ($i = 0; $i < $jours; $i++) {
            $date = $dateDebut->copy()->addDays($i);
            $montant = Paiement::where('statut', 'paye')
                ->whereDate('created_at', $date)
                ->sum('montant') ?? 0;
            
            $chartCAJournalier['labels'][] = $date->format('d/m');
            $chartCAJournalier['data'][] = $montant;
        }

        // Si aucune donnée, mettre des valeurs par défaut pour éviter l'erreur
        if (empty($chartCAJournalier['labels']) || array_sum($chartCAJournalier['data']) == 0) {
            $chartCAJournalier['labels'] = ['Aucune donnée'];
            $chartCAJournalier['data'] = [0];
        }

        // CA par bateau
        $chartCABateau = [
            'labels' => [],
            'data' => []
        ];
        
        $bateaux = Bateau::with(['voyages.reservations.paiements' => function($q) use ($dateDebut, $dateFin) {
            $q->where('statut', 'paye')
              ->whereBetween('created_at', [$dateDebut, $dateFin]);
        }])->get();
        
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

        // Statistiques des paiements en attente
        $paiementsAttente = Paiement::where('statut', 'en_attente')->count();
        $paiementsEchoues = Paiement::where('statut', 'echoue')->count();
        $paiementsRembourses = Paiement::where('statut', 'rembourse')->count();

        // Tendance
        $tendanceMoisPrecedent = Paiement::where('statut', 'paye')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('montant') ?? 0;

        $tendanceMoisActuel = $stats['ca_total'];
        $evolution = $tendanceMoisPrecedent > 0 
            ? (($tendanceMoisActuel - $tendanceMoisPrecedent) / $tendanceMoisPrecedent) * 100 
            : 0;

        return view('superviseur.statistiques.financieres', compact(
            'stats',
            'paiementsParMode',
            'chartCAJournalier',
            'chartCABateau',
            'paiementsAttente',
            'paiementsEchoues',
            'paiementsRembourses',
            'evolution',
            'periode',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * Statistiques des réservations
     */
    public function reservations(Request $request)
    {
        // Période
        $periode = $request->periode ?? 'mois';
        $dateDebut = Carbon::now();
        $dateFin = Carbon::now();

        switch ($periode) {
            case 'aujourdhui':
                $dateDebut = Carbon::today();
                $dateFin = Carbon::today();
                break;
            case 'semaine':
                $dateDebut = Carbon::now()->startOfWeek();
                $dateFin = Carbon::now()->endOfWeek();
                break;
            case 'mois':
                $dateDebut = Carbon::now()->startOfMonth();
                $dateFin = Carbon::now()->endOfMonth();
                break;
            case 'trimestre':
                $dateDebut = Carbon::now()->startOfQuarter();
                $dateFin = Carbon::now()->endOfQuarter();
                break;
            case 'annee':
                $dateDebut = Carbon::now()->startOfYear();
                $dateFin = Carbon::now()->endOfYear();
                break;
            default:
                $dateDebut = Carbon::now()->startOfMonth();
                $dateFin = Carbon::now()->endOfMonth();
        }

        $reservations = Reservation::whereBetween('created_at', [$dateDebut, $dateFin]);

        // Statistiques
        $stats = [
            'total' => $reservations->count(),
            'en_attente' => $reservations->where('statut', 'en_attente')->count(),
            'confirme' => $reservations->where('statut', 'confirme')->count(),
            'paye' => $reservations->where('statut', 'paye')->count(),
            'arrive' => $reservations->where('statut', 'arrive')->count(),
            'annule' => $reservations->where('statut', 'annule')->count(),
            'moyenne_journaliere' => $reservations->count() / max(1, $dateFin->diffInDays($dateDebut) + 1),
        ];

        // Réservations par type
        $reservationsParType = $reservations->selectRaw('type_reservation, COUNT(*) as total')
            ->groupBy('type_reservation')
            ->get();

        // Réservations par jour (graphique)
        $chartJournalier = [
            'labels' => [],
            'data' => []
        ];
        
        $jours = $dateFin->diffInDays($dateDebut) + 1;
        
        for ($i = 0; $i < $jours; $i++) {
            $date = $dateDebut->copy()->addDays($i);
            $count = Reservation::whereDate('created_at', $date)->count();
            
            $chartJournalier['labels'][] = $date->format('d/m');
            $chartJournalier['data'][] = $count;
        }

        // Réservations par statut (graphique)
        $chartStatuts = [
            'labels' => ['En attente', 'Confirmées', 'Payées', 'Arrivées', 'Annulées'],
            'data' => [
                $stats['en_attente'],
                $stats['confirme'],
                $stats['paye'],
                $stats['arrive'],
                $stats['annule']
            ]
        ];

        // Réservations par bateau
        $chartParBateau = [
            'labels' => [],
            'data' => []
        ];
        
        $bateaux = Bateau::with(['voyages.reservations' => function($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('created_at', [$dateDebut, $dateFin]);
        }])->get();
        
        foreach ($bateaux as $bateau) {
            $total = 0;
            foreach ($bateau->voyages as $voyage) {
                $total += $voyage->reservations->count();
            }
            if ($total > 0) {
                $chartParBateau['labels'][] = $bateau->nom;
                $chartParBateau['data'][] = $total;
            }
        }

        // Évolution par rapport à la période précédente
        $periodePrecedenteDebut = $dateDebut->copy()->subDays($dateFin->diffInDays($dateDebut) + 1);
        $periodePrecedenteFin = $dateDebut->copy()->subDay();
        
        $nbPrecedent = Reservation::whereBetween('created_at', [$periodePrecedenteDebut, $periodePrecedenteFin])->count();
        $evolution = $nbPrecedent > 0 
            ? (($stats['total'] - $nbPrecedent) / $nbPrecedent) * 100 
            : 0;

        return view('superviseur.statistiques.reservations', compact(
            'stats',
            'reservationsParType',
            'chartJournalier',
            'chartStatuts',
            'chartParBateau',
            'evolution',
            'periode',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * Statistiques du personnel
     */
    public function personnel(Request $request)
    {
        // Statistiques générales
        $stats = [
            'total' => Personnel::count(),
            'actif' => Personnel::where('statut', 'actif')->count(),
            'inactif' => Personnel::where('statut', 'inactif')->count(),
            'par_role' => Personnel::selectRaw('personnel_role, COUNT(*) as total')
                ->groupBy('personnel_role')
                ->get(),
            'par_service' => Personnel::selectRaw('service, COUNT(*) as total')
                ->groupBy('service')
                ->get(),
            'salaire_moyen' => Personnel::avg('salaire') ?? 0,
            'salaire_min' => Personnel::min('salaire') ?? 0,
            'salaire_max' => Personnel::max('salaire') ?? 0,
            'masse_salariale' => Personnel::sum('salaire') ?? 0,
        ];

        // Ancienneté moyenne
        $ancienneteMoyenne = Personnel::all()->map(function($p) {
            return Carbon::parse($p->date_embauche)->diffInYears(Carbon::now());
        })->avg() ?? 0;

        // Embauches par mois (dernière année)
        $embauchesParMois = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 11; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $count = Personnel::whereMonth('date_embauche', $mois->month)
                ->whereYear('date_embauche', $mois->year)
                ->count();
            
            $embauchesParMois['labels'][] = $mois->format('M Y');
            $embauchesParMois['data'][] = $count;
        }

        // Répartition des salaires par rôle
        $salairesParRole = Personnel::selectRaw('personnel_role, AVG(salaire) as moyenne, MIN(salaire) as min, MAX(salaire) as max')
            ->groupBy('personnel_role')
            ->get();

        return view('superviseur.statistiques.personnel', compact(
            'stats',
            'ancienneteMoyenne',
            'embauchesParMois',
            'salairesParRole'
        ));
    }

    /**
     * Export des statistiques en CSV
     */
    public function export(Request $request, $type)
    {
        $filename = "statistiques_{$type}_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');

        switch ($type) {
            case 'financieres':
                fputcsv($handle, ['Période', 'CA', 'Nombre paiements', 'Moyenne']);
                break;
            case 'reservations':
                fputcsv($handle, ['Période', 'Total', 'En attente', 'Confirmées', 'Payées', 'Arrivées', 'Annulées']);
                break;
            case 'personnel':
                fputcsv($handle, ['Rôle', 'Total', 'Salaire moyen', 'Salaire min', 'Salaire max']);
                $salairesParRole = Personnel::selectRaw('personnel_role, COUNT(*) as total, AVG(salaire) as moyenne, MIN(salaire) as min, MAX(salaire) as max')
                    ->groupBy('personnel_role')
                    ->get();
                foreach ($salairesParRole as $sr) {
                    fputcsv($handle, [
                        $sr->personnel_role,
                        $sr->total ?? 0,
                        number_format($sr->moyenne ?? 0, 2, ',', ' '),
                        number_format($sr->min ?? 0, 2, ',', ' '),
                        number_format($sr->max ?? 0, 2, ',', ' ')
                    ]);
                }
                break;
            default:
                fputcsv($handle, ['Erreur', 'Type de statistique non reconnu']);
                break;
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}