<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'date_reservation',
        'type_reservation',
        'nombre_cargaison',
        'description',
        'poids_cargaison',
        'date_embarquement',
        'date_arrivee',
        'statut',
        'idvoyage',
        'idclient',
        'idpavillon',
        'prix_total'
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
        'date_embarquement' => 'datetime',
        'date_arrivee' => 'datetime',
        'prix_total' => 'decimal:2'
    ];

    // ✅ Statuts autorisés
    const STATUTS = ['en_attente', 'confirme', 'annule', 'arrive', 'paye'];

    // ✅ Types de réservation autorisés
    const TYPES = ['passage', 'mixte', 'cargaison'];

    /**
     * Relation avec le voyage
     * Une réservation appartient à un voyage
     */
    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'idvoyage');
    }

    /**
     * Relation avec le client
     * Une réservation appartient à un client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    /**
     * Relation avec les paiements
     * Une réservation peut avoir plusieurs paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'idreservation');
    }

    /**
     * Relation avec le pavillon
     * Une réservation appartient à un pavillon
     */
    public function pavillon()
    {
        return $this->belongsTo(Pavillon::class, 'idpavillon');
    }

    /**
     * Relation avec les pavillons via la table reserve (many-to-many)
     * Pour les réservations qui ont plusieurs pavillons
     */
    public function pavillons()
    {
        return $this->belongsToMany(Pavillon::class, 'reserve', 'idreservation', 'idpavillon')
                    ->withPivot('prix')
                    ->withTimestamps();
    }

    /**
     * Relation avec le bateau via le voyage
     */
    public function bateau()
    {
        return $this->hasOneThrough(
            Bateau::class,
            Voyage::class,
            'id',           // Clé étrangère sur voyages (id)
            'id',           // Clé étrangère sur bateaux (id)
            'idvoyage',     // Clé locale sur reservations
            'idbateau'      // Clé locale sur voyages
        );
    }

    /**
     * Vérifier si la réservation est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la réservation est confirmée
     */
    public function isConfirmee(): bool
    {
        return $this->statut === 'confirme';
    }

    /**
     * Vérifier si la réservation est payée
     */
    public function isPayee(): bool
    {
        return $this->statut === 'paye';
    }

    /**
     * Vérifier si la réservation est arrivée
     */
    public function isArrivee(): bool
    {
        return $this->statut === 'arrive';
    }

    /**
     * Vérifier si la réservation est annulée
     */
    public function isAnnulee(): bool
    {
        return $this->statut === 'annule';
    }

    /**
     * Obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        $statuts = [
            'en_attente' => 'En attente',
            'confirme' => 'Confirmée',
            'paye' => 'Payée',
            'arrive' => 'Arrivée',
            'annule' => 'Annulée'
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Obtenir la couleur du statut pour l'affichage
     */
    public function getStatutCouleurAttribute(): string
    {
        $couleurs = [
            'en_attente' => 'yellow',
            'confirme' => 'blue',
            'paye' => 'green',
            'arrive' => 'purple',
            'annule' => 'red'
        ];
        return $couleurs[$this->statut] ?? 'gray';
    }

    /**
     * Obtenir la classe CSS pour le badge de statut
     */
    public function getStatutBadgeAttribute(): string
    {
        $badges = [
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'confirme' => 'bg-blue-100 text-blue-800',
            'paye' => 'bg-green-100 text-green-800',
            'arrive' => 'bg-purple-100 text-purple-800',
            'annule' => 'bg-red-100 text-red-800'
        ];
        return $badges[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obtenir le type en français
     */
    public function getTypeFrancaisAttribute(): string
    {
        $types = [
            'passage' => 'Passage',
            'mixte' => 'Mixte',
            'cargaison' => 'Cargaison'
        ];
        return $types[$this->type_reservation] ?? $this->type_reservation;
    }

    /**
     * Générer un token unique pour le paiement direct
     */
    public function generatePaiementToken(): string
    {
        return hash_hmac('sha256', $this->id . $this->idclient . $this->created_at, config('app.key'));
    }

    /**
     * Vérifier si le paiement est complet (payé)
     */
    public function isPaiementComplet(): bool
    {
        return $this->paiements()->where('statut', 'paye')->exists();
    }

    /**
     * Obtenir le montant total payé
     */
    public function getMontantPayeAttribute(): float
    {
        return $this->paiements()->where('statut', 'paye')->sum('montant') ?? 0;
    }

    /**
     * Obtenir le montant restant à payer
     */
    public function getMontantRestantAttribute(): float
    {
        return max(0, $this->prix_total - $this->montant_paye);
    }

    /**
     * Vérifier si la réservation est entièrement payée
     */
    public function isEntierementPayee(): bool
    {
        return $this->montant_restant <= 0;
    }

    /**
     * Scope pour les réservations en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les réservations confirmées
     */
    public function scopeConfirmee($query)
    {
        return $query->where('statut', 'confirme');
    }

    /**
     * Scope pour les réservations payées
     */
    public function scopePayee($query)
    {
        return $query->where('statut', 'paye');
    }

    /**
     * Scope pour les réservations arrivées
     */
    public function scopeArrivee($query)
    {
        return $query->where('statut', 'arrive');
    }

    /**
     * Scope pour les réservations annulées
     */
    public function scopeAnnulee($query)
    {
        return $query->where('statut', 'annule');
    }

    /**
     * Scope pour les réservations par client
     */
    public function scopeParClient($query, $clientId)
    {
        return $query->where('idclient', $clientId);
    }

    /**
     * Scope pour les réservations par voyage
     */
    public function scopeParVoyage($query, $voyageId)
    {
        return $query->where('idvoyage', $voyageId);
    }

    /**
     * Scope pour les réservations par période
     */
    public function scopeEntreDates($query, $debut, $fin)
    {
        return $query->whereBetween('date_reservation', [$debut, $fin]);
    }

    /**
     * Scope pour les réservations par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_reservation', $type);
    }

    /**
     * Scope pour les réservations par pavillon
     */
    public function scopeParPavillon($query, $pavillonId)
    {
        return $query->where('idpavillon', $pavillonId);
    }

    /**
     * Scope pour les réservations avec paiement effectué
     */
    public function scopeAvecPaiement($query)
    {
        return $query->whereHas('paiements', function($q) {
            $q->where('statut', 'paye');
        });
    }

    /**
     * Scope pour les réservations sans paiement
     */
    public function scopeSansPaiement($query)
    {
        return $query->whereDoesntHave('paiements', function($q) {
            $q->where('statut', 'paye');
        });
    }

    /**
     * Obtenir la date de réservation formatée
     */
    public function getDateReservationFormateeAttribute(): string
    {
        return $this->date_reservation->format('d/m/Y H:i');
    }

    /**
     * Obtenir la date d'embarquement formatée
     */
    public function getDateEmbarquementFormateeAttribute(): string
    {
        return $this->date_embarquement->format('d/m/Y H:i');
    }

    /**
     * Obtenir la date d'arrivée formatée
     */
    public function getDateArriveeFormateeAttribute(): string
    {
        return $this->date_arrivee ? $this->date_arrivee->format('d/m/Y H:i') : 'Non arrivé';
    }

    /**
     * Obtenir le prix total formaté
     */
    public function getPrixTotalFormateAttribute(): string
    {
        return number_format($this->prix_total, 0, ',', ' ') . ' FC';
    }

    /* Documentation complète de l'application - toutes les fonctionnalités expliquées */
}