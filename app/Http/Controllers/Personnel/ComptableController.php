<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\Client;
use Carbon\Carbon;

class ComptableController extends Controller
{
    /**
     * Affiche le tableau de bord du comptable
     */
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('personnel');

        // Statistiques financières
        $stats = [
            // Paiements du jour
            'paiements_aujourdhui' => Paiement::whereDate('created_at', Carbon::today())->count(),
            'ca_aujourdhui' => Paiement::whereDate('created_at', Carbon::today())
                ->where('statut', 'paye')
                ->sum('montant') ?? 0,

            // Paiements en attente
            'paiements_attente' => Paiement::where('statut', 'en_attente')->count(),
            'montant_attente' => Paiement::where('statut', 'en_attente')->sum('montant') ?? 0,

            // Paiements échoués
            'paiements_echoues' => Paiement::where('statut', 'echoue')->count(),

            // CA par période
            'ca_semaine' => Paiement::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('statut', 'paye')->sum('montant') ?? 0,

            'ca_mois' => Paiement::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('statut', 'paye')
                ->sum('montant') ?? 0,

            'ca_total' => Paiement::where('statut', 'paye')->sum('montant') ?? 0,

            // Statistiques globales
            'total_paiements' => Paiement::count(),
            'total_paye' => Paiement::where('statut', 'paye')->count(),
            'total_rembourse' => Paiement::where('statut', 'rembourse')->count(),
        ];

        // Derniers paiements
        $derniersPaiements = Paiement::with(['reservation.client'])
            ->latest()
            ->take(10)
            ->get();

        // Paiements en attente
        $paiementsAttente = Paiement::with(['reservation.client'])
            ->where('statut', 'en_attente')
            ->latest()
            ->take(5)
            ->get();

        return view('personnel.comptable.dashboard', compact(
            'user',
            'stats',
            'derniersPaiements',
            'paiementsAttente'
        ));
    }

    /**
     * Liste des paiements
     */
    public function paiements(Request $request)
    {
        $query = Paiement::with(['reservation.client']);

        // Filtres
        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('mode') && $request->mode != 'tous') {
            $query->where('mode_paiement', $request->mode);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        if ($request->filled('client')) {
            $search = $request->client;
            $query->whereHas('reservation.client', function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $paiements = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques
        $statistiques = [
            'total' => Paiement::count(),
            'paye' => Paiement::where('statut', 'paye')->count(),
            'en_attente' => Paiement::where('statut', 'en_attente')->count(),
            'echoue' => Paiement::where('statut', 'echoue')->count(),
            'rembourse' => Paiement::where('statut', 'rembourse')->count(),
        ];

        $modes = ['MOMO', 'CASH', 'VIREMENT', 'MAISHA_PAY'];

        return view('personnel.comptable.paiements', compact('paiements', 'statistiques', 'request', 'modes'));
    }

    /**
     * Détail d'un paiement
     */
    public function showPaiement($id)
    {
        $paiement = Paiement::with(['reservation.client', 'reservation.voyage.bateau'])
            ->findOrFail($id);

        return view('personnel.comptable.paiement-show', compact('paiement'));
    }

    /**
     * Valider un paiement
     */
    public function validerPaiement($id)
    {
        $paiement = Paiement::findOrFail($id);

        if ($paiement->statut === 'paye') {
            return redirect()->back()->with('info', 'Ce paiement est déjà validé.');
        }

        $paiement->statut = 'paye';
        $paiement->save();

        // Mettre à jour le statut de la réservation
        if ($paiement->reservation) {
            $reservation = $paiement->reservation;
            $reservation->statut = 'paye';
            $reservation->save();
        }

        return redirect()->back()->with('success', 'Paiement validé avec succès.');
    }

    /**
     * Refuser un paiement
     */
    public function refuserPaiement($id)
    {
        $paiement = Paiement::findOrFail($id);

        if ($paiement->statut === 'paye') {
            return redirect()->back()->with('error', 'Impossible de refuser un paiement déjà validé.');
        }

        $paiement->statut = 'echoue';
        $paiement->save();

        return redirect()->back()->with('success', 'Paiement refusé.');
    }

    /**
     * Exporter les paiements en CSV
     */
    public function exportPaiements(Request $request)
    {
        $query = Paiement::with(['reservation.client']);

        if ($request->filled('statut') && $request->statut != 'tous') {
            $query->where('statut', $request->statut);
        }

        $paiements = $query->get();

        $filename = "paiements_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['ID', 'Client', 'Montant', 'Devise', 'Mode', 'Date paiement', 'Statut']);

        foreach ($paiements as $p) {
            fputcsv($handle, [
                $p->id,
                $p->reservation->client->nom . ' ' . $p->reservation->client->prenom ?? 'N/A',
                number_format($p->montant, 2, ',', ' '),
                $p->devise,
                $p->mode_paiement,
                Carbon::parse($p->date_paiement)->format('d/m/Y H:i'),
                $p->statut
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Générer un rapport financier
     */
    public function rapportFinancier(Request $request)
    {
        $dateDebut = $request->date_debut ? Carbon::parse($request->date_debut) : Carbon::now()->startOfMonth();
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : Carbon::now()->endOfMonth();

        $paiements = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'paye')
            ->get();

        $stats = [
            'total_paiements' => $paiements->count(),
            'montant_total' => $paiements->sum('montant') ?? 0,
            'par_mode' => $paiements->groupBy('mode_paiement')->map(function($item) {
                return [
                    'count' => $item->count(),
                    'montant' => $item->sum('montant')
                ];
            }),
            'par_devise' => $paiements->groupBy('devise')->map(function($item) {
                return [
                    'count' => $item->count(),
                    'montant' => $item->sum('montant')
                ];
            }),
        ];

        return view('personnel.comptable.rapport', compact('stats', 'dateDebut', 'dateFin'));
    }
}