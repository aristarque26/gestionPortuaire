<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voyage extends Model
{
    use HasFactory;

    protected $table = 'voyages';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'code_voyage',
        'description',
        'statut',
        'date_depart',
        'idbateau'
    ];

    protected $casts = [
        'date_depart' => 'datetime'
    ];

    /**
     * Relation avec le bateau
     */
    public function bateau()
    {
        return $this->belongsTo(Bateau::class, 'idbateau');
    }

    /**
     * Relation avec les réservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idvoyage');
    }

    /**
     * Relation avec les trajets
     */
    public function trajets()
    {
        return $this->hasMany(Trajet::class, 'idvoyage');
    }

    /**
     * Relation avec les ports via les trajets
     */
    public function ports()
    {
        return $this->hasManyThrough(
            Port::class,
            Trajet::class,
            'idvoyage',
            'id',
            'id',
            'idport'
        );
    }

    /**
     * Relation avec les paiements via les réservations
     */
    public function paiements()
    {
        return $this->hasManyThrough(
            Paiement::class,
            Reservation::class,
            'idvoyage',
            'idreservation',
            'id',
            'id'
        );
    }

    /**
     * Vérifier si le voyage est prévu
     */
    public function isPrevu(): bool
    {
        return $this->statut === 'prevu';
    }

    /**
     * Vérifier si le voyage est en cours
     */
    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifier si le voyage est terminé
     */
    public function isTermine(): bool
    {
        return $this->statut === 'termine';
    }

    /**
     * Vérifier si le voyage est annulé
     */
    public function isAnnule(): bool
    {
        return $this->statut === 'annule';
    }

    /**
     * Nombre de places déjà réservées pour ce voyage
     */
    public function placesReservees(): int
    {
        return $this->reservations()->count();
    }

    /**
     * Places disponibles (capacité bateau - places réservées)
     */
    public function placesDisponibles(): int
    {
        $capacite = $this->bateau->capacite_passager ?? 0;
        return max(0, $capacite - $this->placesReservees());
    }

    /**
     * Nombre de tonnes déjà réservées pour ce voyage
     */
    public function tonnesReservees(): float
    {
        return $this->reservations()->sum('poids_cargaison') ?? 0;
    }

    /**
     * Tonnes disponibles
     */
    public function tonnesDisponibles(): float
    {
        $capacite = $this->bateau->capacite_cargaison ?? 0;
        return max(0, $capacite - $this->tonnesReservees());
    }

    /**
     * Chiffre d'affaires total du voyage
     */
    public function getCATotalAttribute(): float
    {
        return $this->reservations()->sum('prix_total') ?? 0;
    }

    /**
     * Nombre total de passagers pour ce voyage
     */
    public function getTotalPassagersAttribute(): int
    {
        return $this->reservations()
                    ->where('type_reservation', 'passage')
                    ->sum('nombre_cargaison') ?? 0;
    }

    /**
     * Nombre total de cargaisons pour ce voyage
     */
    public function getTotalCargaisonAttribute(): int
    {
        return $this->reservations()
                    ->where('type_reservation', 'cargaison')
                    ->sum('nombre_cargaison') ?? 0;
    }

    /**
     * Taux d'occupation du voyage
     */
    public function getTauxOccupationAttribute(): float
    {
        $capacite = $this->bateau->capacite_totale ?? 1;
        $reservations = $this->reservations()->count();
        return round(($reservations / $capacite) * 100, 2);
    }

    /**
     * Scope pour les voyages prévus
     */
    public function scopePrevu($query)
    {
        return $query->where('statut', 'prevu');
    }

    /**
     * Scope pour les voyages en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les voyages terminés
     */
    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope pour les voyages annulés
     */
    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annule');
    }

    /**
     * Scope pour les voyages à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('statut', 'prevu')
                     ->where('date_depart', '>=', Carbon::now());
    }

    /**
     * Scope pour les voyages passés
     */
    public function scopePasses($query)
    {
        return $query->where('statut', 'termine')
                     ->orWhere('date_depart', '<', Carbon::now());
    }

    /**
     * Scope pour les voyages par période
     */
    public function scopeEntreDates($query, $debut, $fin)
    {
        return $query->whereBetween('date_depart', [$debut, $fin]);
    }

    /**
     * Scope pour les voyages par bateau
     */
    public function scopeParBateau($query, $bateauId)
    {
        return $query->where('idbateau', $bateauId);
    }
}