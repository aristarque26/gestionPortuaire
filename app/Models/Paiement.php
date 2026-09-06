<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'montant',
        'devise',
        'mode_paiement',
        'date_paiement',
        'statut',
        'idreservation'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2'
    ];

    // ✅ Statuts autorisés pour la colonne 'statut'
    const STATUTS = ['paye', 'en_attente', 'echoue', 'rembourse'];

    // ✅ Modes de paiement autorisés
    const MODES = ['MOMO', 'CASH', 'VIREMENT', 'MAISHA_PAY'];

    // ✅ Devises autorisées
    const DEVISES = ['USD', 'CDF', 'FC'];

    /**
     * Relation avec la réservation
     * Un paiement appartient à une réservation
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }

    /**
     * Relation avec le client via la réservation
     * Un paiement a un client à travers la réservation
     */
    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Reservation::class,
            'id',           // Clé étrangère sur reservations (id)
            'id',           // Clé étrangère sur client (id)
            'idreservation', // Clé locale sur paiements
            'idclient'      // Clé locale sur reservations
        );
    }

    /**
     * Vérifier si le paiement est effectué
     */
    public function isPaye(): bool
    {
        return $this->statut === 'paye';
    }

    /**
     * Vérifier si le paiement est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si le paiement est échoué
     */
    public function isEchoue(): bool
    {
        return $this->statut === 'echoue';
    }

    /**
     * Vérifier si le paiement est remboursé
     */
    public function isRembourse(): bool
    {
        return $this->statut === 'rembourse';
    }

    /**
     * Obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        $statuts = [
            'paye' => 'Payé',
            'en_attente' => 'En attente',
            'echoue' => 'Échoué',
            'rembourse' => 'Remboursé'
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Obtenir la couleur du statut pour l'affichage
     */
    public function getStatutCouleurAttribute(): string
    {
        $couleurs = [
            'paye' => 'green',
            'en_attente' => 'yellow',
            'echoue' => 'red',
            'rembourse' => 'gray'
        ];
        return $couleurs[$this->statut] ?? 'gray';
    }

    /**
     * Obtenir la classe CSS pour le badge de statut
     */
    public function getStatutBadgeAttribute(): string
    {
        $badges = [
            'paye' => 'bg-green-100 text-green-800',
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'echoue' => 'bg-red-100 text-red-800',
            'rembourse' => 'bg-gray-100 text-gray-800'
        ];
        return $badges[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Obtenir le mode de paiement en français
     */
    public function getModeFrancaisAttribute(): string
    {
        $modes = [
            'MOMO' => 'Mobile Money',
            'CASH' => 'Espèces',
            'VIREMENT' => 'Virement bancaire',
            'MAISHA_PAY' => 'Maisha Pay'
        ];
        return $modes[$this->mode_paiement] ?? $this->mode_paiement;
    }

    /**
     * Scope pour les paiements effectués
     */
    public function scopePaye($query)
    {
        return $query->where('statut', 'paye');
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les paiements échoués
     */
    public function scopeEchoue($query)
    {
        return $query->where('statut', 'echoue');
    }

    /**
     * Scope pour les paiements remboursés
     */
    public function scopeRembourse($query)
    {
        return $query->where('statut', 'rembourse');
    }

    /**
     * Scope pour les paiements par période
     */
    public function scopeEntreDates($query, $debut, $fin)
    {
        return $query->whereBetween('date_paiement', [$debut, $fin]);
    }

    /**
     * Scope pour les paiements par mode
     */
    public function scopeParMode($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }

    /**
     * Scope pour les paiements par devise
     */
    public function scopeParDevise($query, $devise)
    {
        return $query->where('devise', $devise);
    }

    /**
     * Scope pour les paiements par réservation
     */
    public function scopeParReservation($query, $reservationId)
    {
        return $query->where('idreservation', $reservationId);
    }

    /**
     * Obtenir le montant formaté
     */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' ' . $this->devise;
    }

    /**
     * Obtenir la date formatée
     */
    public function getDateFormateeAttribute(): string
    {
        return $this->date_paiement->format('d/m/Y H:i');
    }
}