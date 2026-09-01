<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Voyage;
use App\Models\Pavillon;
use App\Models\Paiement;
use App\Services\BrevoEmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        $reservations = Reservation::where('idclient', $client->id)
            ->with('voyage', 'pavillon')
            ->latest()
            ->paginate(10);
        
        return view('client.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $voyages = Voyage::where('statut', 'prevu')
            ->where('date_depart', '>', now())
            ->with(['bateau', 'trajets.ports.quais'])
            ->get();

        $pavillons = Pavillon::all();

        return view('client.reservations.create', compact('voyages', 'pavillons'));
    }

    public function store(Request $request)
    {
        $client = Auth::user()->client;

        $request->validate([
            'idvoyage'             => 'required|exists:voyages,id',
            'type_reservation'     => 'required|in:passage,cargaison,mixte',
            'nombre_cargaison'     => 'nullable|integer',
            'poids_cargaison'      => 'nullable|numeric',
            'description'          => 'nullable|string',
            'idpavillon_passager'  => 'required_if:type_reservation,passage,mixte|exists:pavillons,id',
            'idpavillon_cargaison' => 'required_if:type_reservation,cargaison,mixte|exists:pavillons,id',
        ]);

        $voyage = Voyage::findOrFail($request->idvoyage);
        $prixTotal = 0;

        // Type passage ou mixte → pavillon passager
        if (in_array($request->type_reservation, ['passage', 'mixte'])) {
            $pavillonPassager = Pavillon::find($request->idpavillon_passager);
            
            if ($pavillonPassager->placesDisponiblesPourVoyage($voyage->id) <= 0) {
                return back()->with('error', 'Le pavillon passager est complet pour ce voyage.');
            }
            
            $prixTotal += $pavillonPassager->prix_unitaire;
        }

        // Type cargaison ou mixte → pavillon cargaison (utilisation de prix_tonne)
        if (in_array($request->type_reservation, ['cargaison', 'mixte']) && $request->poids_cargaison) {
            $pavillonCargaison = Pavillon::find($request->idpavillon_cargaison);
            
            if ($pavillonCargaison->placesDisponiblesPourVoyage($voyage->id) <= 0) {
                return back()->with('error', 'Le pavillon cargaison est complet pour ce voyage.');
            }
            
            $prixTotal += $pavillonCargaison->prix_tonne * $request->poids_cargaison;
        }

        if ($voyage->placesDisponibles() <= 0) {
            return back()->with('error', 'Ce bateau est complet pour ce voyage.');
        }

        \Log::info('📝 Création d\'une réservation de type ' . $request->type_reservation);

        $reservation = Reservation::create([
            'date_reservation'    => now(),
            'type_reservation'    => $request->type_reservation,
            'nombre_cargaison'    => $request->nombre_cargaison,
            'description'         => $request->description,
            'poids_cargaison'     => $request->poids_cargaison,
            'date_embarquement'   => $voyage->date_depart,
            'statut'              => 'en_attente',
            'idvoyage'            => $request->idvoyage,
            'idclient'            => $client->id,
            'idpavillon'          => $request->idpavillon_passager ?? $request->idpavillon_cargaison,
            'prix_total'          => $prixTotal,
        ]);

        \Log::info('Réservation créée avec ID ' . $reservation->id);

        try {
            $htmlContent = view('emails.reservation-confirmation', ['reservation' => $reservation])->render();
            BrevoEmailService::send($client->email, 'Confirmation de votre réservation', $htmlContent);
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email: ' . $e->getMessage());
        }

        return redirect()->route('client.reservations.show', $reservation->id)
            ->with('success', 'Réservation créée avec succès. Un email de confirmation vous a été envoyé.');
    }

    public function show($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
            ->with(['voyage.bateau', 'voyage.trajets.ports.quais', 'pavillon'])
            ->findOrFail($id);
        
        return view('client.reservations.show', compact('reservation'));
    }

    public function destroy($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
            ->where('statut', 'en_attente')
            ->findOrFail($id);
        
        $reservation->delete();

        return redirect()->route('client.reservations.index')
            ->with('success', 'Réservation annulée avec succès.');
    }

    public function pagePaiement($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
                        ->where('statut', 'confirme')
                        ->findOrFail($id);
        
        return view('client.paiement.index', compact('reservation'));
    }

public function effectuerPaiement($id)
{
    $client = Auth::user()->client;
    $reservation = Reservation::where('idclient', $client->id)
                    ->where('statut', 'confirme')
                    ->findOrFail($id);
    
    // Création du paiement
    $paiement = Paiement::create([
        'idreservation'  => $reservation->id,
        'montant'        => $reservation->prix_total,
        'devise'         => 'CDF',
        'mode_paiement'  => 'MAISHA_PAY',
        'date_paiement'  => now(),
        'statut'         => 'paye'
    ]);
    
    $reservation->statut = 'paye';
    $reservation->save();
    
    // Génération du PDF
    $pdf = Pdf::loadView('pdf.facture', ['reservation' => $reservation]);
    $pdfContent = $pdf->output();
    
    // ✅ Déclarer $contenu ici (en dehors du try)
    $contenu = view('emails.paiement-confirmation', ['reservation' => $reservation])->render();
    
    try {
        BrevoEmailService::sendWithAttachment(
            $client->email,
            'Paiement reçu - KivuPort',
            $contenu,
            $pdfContent,
            'facture_' . $reservation->id . '.pdf'
        );
    } catch (\Exception $e) {
        \Log::error('Erreur envoi email paiement: ' . $e->getMessage());
        BrevoEmailService::send($client->email, 'Paiement reçu - KivuPort', $contenu);
    }
    
    return redirect()->route('client.reservations.index')
        ->with('success', 'Paiement effectué avec succès via Maisha Pay !');
}

    public function paiementDirect($id, $token)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->generatePaiementToken() !== $token) {
            abort(403, 'Lien de paiement invalide.');
        }

        if ($reservation->statut !== 'confirme') {
            abort(404, 'Cette réservation ne peut pas être payée.');
        }

        Auth::loginUsingId($reservation->idclient);

        return redirect()->route('client.reservations.paiement', ['id' => $reservation->id]);
    }

    public function telechargerFacture($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
                        ->where('statut', 'paye')
                        ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.facture', ['reservation' => $reservation]);
        return $pdf->download('facture_' . $reservation->id . '.pdf');
    }
}