<?php

namespace App\Http\Controllers\Superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Paiement;
use App\Models\Voyage;
use App\Models\Personnel;
use Carbon\Carbon;

class RapportController extends Controller
{
    /**
     * Page de génération de rapports
     */
    public function index()
    {
        return view('superviseur.rapports.index');
    }

    /**
     * Générer un rapport
     */
    public function generer(Request $request)
    {
        $request->validate([
            'type' => 'required|in:reservations,paiements,voyages,personnel',
            'periode' => 'required|in:aujourdhui,semaine,mois,trimestre,annee,personnalise',
            'date_debut' => 'nullable|date|required_if:periode,personnalise',
            'date_fin' => 'nullable|date|required_if:periode,personnalise|after_or_equal:date_debut',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        // Définir la période
        switch ($request->periode) {
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
            case 'personnalise':
                $dateDebut = Carbon::parse($request->date_debut);
                $dateFin = Carbon::parse($request->date_fin);
                break;
        }

        // Générer les données selon le type
        $donnees = [];
        $titre = '';

        switch ($request->type) {
            case 'reservations':
                $donnees = Reservation::with(['client', 'voyage.bateau'])
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->get();
                $titre = "Rapport des réservations du {$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')}";
                $entetes = ['ID', 'Client', 'Bateau', 'Type', 'Date réservation', 'Date embarquement', 'Prix total', 'Statut'];
                break;

            case 'paiements':
                $donnees = Paiement::with(['reservation.client'])
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->where('statut', 'paye')
                    ->get();
                $titre = "Rapport des paiements du {$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')}";
                $entetes = ['ID', 'Client', 'Montant', 'Devise', 'Mode', 'Date paiement', 'Statut'];
                break;

            case 'voyages':
                $donnees = Voyage::with('bateau')
                    ->whereBetween('date_depart', [$dateDebut, $dateFin])
                    ->get();
                $titre = "Rapport des voyages du {$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')}";
                $entetes = ['Code', 'Bateau', 'Description', 'Date départ', 'Statut', 'Nb réservations', 'CA total'];
                break;

            case 'personnel':
                $donnees = Personnel::with('user')
                    ->whereBetween('created_at', [$dateDebut, $dateFin])
                    ->get();
                $titre = "Rapport du personnel du {$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')}";
                $entetes = ['Matricule', 'Nom', 'Prénom', 'Poste', 'Service', 'Rôle', 'Salaire', 'Statut'];
                break;
        }

        // Si le format est CSV, on exporte directement
        if ($request->format === 'csv') {
            return $this->exportCSV($titre, $entetes, $donnees, $request->type);
        }

        // Si PDF/Excel, on passe à la vue
        return view('superviseur.rapports.preview', compact(
            'titre',
            'entetes',
            'donnees',
            'dateDebut',
            'dateFin',
            'request'
        ));
    }

    /**
     * Export CSV
     */
    private function exportCSV($titre, $entetes, $donnees, $type)
    {
        $filename = "rapport_{$type}_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        // Titre
        fputcsv($handle, [$titre]);
        fputcsv($handle, []);
        
        // En-têtes
        fputcsv($handle, $entetes);
        
        // Données
        foreach ($donnees as $item) {
            $row = $this->formatData($item, $type);
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Formater les données selon le type
     */
    private function formatData($item, $type)
    {
        switch ($type) {
            case 'reservations':
                return [
                    $item->id,
                    $item->client->nom . ' ' . $item->client->prenom,
                    $item->voyage->bateau->nom ?? 'N/A',
                    $item->type_reservation,
                    Carbon::parse($item->date_reservation)->format('d/m/Y H:i'),
                    Carbon::parse($item->date_embarquement)->format('d/m/Y H:i'),
                    number_format($item->prix_total, 2, ',', ' '),
                    $item->statut
                ];
            case 'paiements':
                return [
                    $item->id,
                    $item->reservation->client->nom . ' ' . $item->reservation->client->prenom,
                    number_format($item->montant, 2, ',', ' '),
                    $item->devise,
                    $item->mode_paiement,
                    Carbon::parse($item->date_paiement)->format('d/m/Y H:i'),
                    $item->statut
                ];
            case 'voyages':
                $nbRes = $item->reservations->count();
                $caTotal = $item->reservations->sum('prix_total') ?? 0;
                return [
                    $item->code_voyage,
                    $item->bateau->nom ?? 'N/A',
                    $item->description ?? '',
                    Carbon::parse($item->date_depart)->format('d/m/Y H:i'),
                    $item->statut,
                    $nbRes,
                    number_format($caTotal, 2, ',', ' ')
                ];
            case 'personnel':
                return [
                    $item->matricule,
                    $item->user->name ?? 'N/A',
                    $item->user->prenom ?? 'N/A',
                    $item->poste,
                    $item->service,
                    $item->personnel_role,
                    number_format($item->salaire, 2, ',', ' '),
                    $item->statut
                ];
        }
    }
}