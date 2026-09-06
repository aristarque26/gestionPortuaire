<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\Client;
use Carbon\Carbon;

class CaissierController extends Controller
{
    /**
     * Affiche le tableau de bord du caissier
     */
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('personnel');

        // Statistiques du caissier
        $stats = [
            'paiements_aujourdhui' => Paiement::whereDate('created_at', Carbon::today())->count(),
            'ca_aujourdhui' => Paiement::whereDate('created_at', Carbon::today())
                ->where('statut', 'paye')
                ->sum('montant') ?? 0,

            'paiements_attente' => Paiement::where('statut', 'en_attente')->count(),
            'paiements_aujourdhui_attente' => Paiement::whereDate('created_at', Carbon::today())
                ->where('statut', 'en_attente')
                ->count(),

            'total_paiements' => Paiement::count(),
            'total_paye' => Paiement::where('statut', 'paye')->count(),
        ];

        // Paiements en attente à encaisser
        $paiementsAttente = Paiement::with(['reservation.client'])
            ->where('statut', 'en_attente')
            ->latest()
            ->take(10)
            ->get();

        // Derniers paiements effectués
        $derniersPaiements = Paiement::with(['reservation.client'])
            ->where('statut', 'paye')
            ->latest()
            ->take(5)
            ->get();

        return view('personnel.caissier.dashboard', compact(
            'user',
            'stats',
            'paiementsAttente',
            'derniersPaiements'
        ));
    }

    /**
     * Liste des paiements à encaisser
     */
    public function paiementsAttente(Request $request)
    {
        $query = Paiement::with(['reservation.client'])
            ->where('statut', 'en_attente');

        if ($request->filled('client')) {
            $search = $request->client;
            $query->whereHas('reservation.client', function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $paiements = $query->orderBy('created_at')->paginate(15);

        return view('personnel.caissier.paiements-attente', compact('paiements', 'request'));
    }

    /**
     * Encaisser un paiement
     */
    public function encaisserPaiement($id)
    {
        $paiement = Paiement::findOrFail($id);

        if ($paiement->statut === 'paye') {
            return redirect()->back()->with('info', 'Ce paiement est déjà encaissé.');
        }

        if ($paiement->statut === 'echoue' || $paiement->statut === 'rembourse') {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être encaissé.');
        }

        $paiement->statut = 'paye';
        $paiement->save();

        // Mettre à jour le statut de la réservation
        if ($paiement->reservation) {
            $reservation = $paiement->reservation;
            $reservation->statut = 'paye';
            $reservation->save();
        }

        return redirect()->back()->with('success', 'Paiement encaissé avec succès.');
    }

    /**
     * Encaisser plusieurs paiements
     */
    public function encaisserMultiple(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun paiement sélectionné.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $paiement = Paiement::find($id);
            if ($paiement && $paiement->statut === 'en_attente') {
                $paiement->statut = 'paye';
                $paiement->save();

                if ($paiement->reservation) {
                    $paiement->reservation->statut = 'paye';
                    $paiement->reservation->save();
                }
                $count++;
            }
        }

        return redirect()->back()->with('success', "{$count} paiement(s) encaissé(s) avec succès.");
    }

    /**
     * Historique des paiements encaissés
     */
    public function historique(Request $request)
    {
        $query = Paiement::with(['reservation.client'])
            ->where('statut', 'paye');

        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(15);

        return view('personnel.caissier.historique', compact('paiements', 'request'));
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

        $filename = "caissier_paiements_" . date('Y-m-d') . ".csv";
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
     * Statistiques du caissier
     */
    public function statistiques()
    {
        $stats = [
            'aujourdhui' => [
                'paye' => Paiement::whereDate('created_at', Carbon::today())->where('statut', 'paye')->count(),
                'montant' => Paiement::whereDate('created_at', Carbon::today())->where('statut', 'paye')->sum('montant') ?? 0,
                'attente' => Paiement::whereDate('created_at', Carbon::today())->where('statut', 'en_attente')->count(),
            ],
            'semaine' => [
                'paye' => Paiement::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->where('statut', 'paye')->count(),
                'montant' => Paiement::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->where('statut', 'paye')->sum('montant') ?? 0,
            ],
            'mois' => [
                'paye' => Paiement::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->where('statut', 'paye')->count(),
                'montant' => Paiement::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->where('statut', 'paye')->sum('montant') ?? 0,
            ],
        ];

        return view('personnel.caissier.statistiques', compact('stats'));
    }
}