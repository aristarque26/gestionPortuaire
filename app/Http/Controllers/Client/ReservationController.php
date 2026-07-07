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

class ReservationController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;
        $reservations = Reservation::where('idclient', $client->id)
            ->with('voyage', 'pavillon')
            ->latest()
            ->paginate(10); // ✅ CHANGEMENT : pagination au lieu de get()
        
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

        // Envoi de l'email de confirmation
        try {
            $htmlContent = view('emails.reservation-confirmation', ['reservation' => $reservation])->render();
            BrevoEmailService::send($client->email, 'Confirmation de votre réservation', $htmlContent);
        } catch (\Exception $e) {
            // On ne bloque pas la réservation si l'email échoue
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

    // 🔥 PAGE DE PAIEMENT (Affiche le formulaire)
    public function pagePaiement($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
                        ->where('statut', 'confirme')
                        ->findOrFail($id);
        
        return view('client.paiement.index', compact('reservation'));
    }

    // 🔥 EFFECTUER LE PAIEMENT (Simulation Maisha Pay)
    public function effectuerPaiement($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('idclient', $client->id)
                        ->where('statut', 'confirme')
                        ->findOrFail($id);
        
        // ======================================================
        // SIMULATION MAISHA PAY
        // En production, remplacer ce bloc par un appel API réel
        // ======================================================
        
        // Création de l'enregistrement dans la table paiements
        $paiement = Paiement::create([
            'idreservation'  => $reservation->id,
            'montant'        => $reservation->prix_total,
            'devise'         => 'FC',
            'mode_paiement'  => 'MAISHA_PAY',
            'date_paiement'  => now(),
            'statut'         => 'paye'
        ]);
        
        // Mise à jour du statut de la réservation
        $reservation->statut = 'paye';
        $reservation->save();
        
        // Envoi de l'email de confirmation de paiement (Email 2)
        try {
            $contenu = view('emails.paiement-confirmation', ['reservation' => $reservation])->render();
            BrevoEmailService::send($client->email, 'Paiement reçu - KivuPort', $contenu);
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email paiement: ' . $e->getMessage());
        }
        
        return redirect()->route('client.reservations.index')
            ->with('success', 'Paiement effectué avec succès via Maisha Pay !');
    }
}