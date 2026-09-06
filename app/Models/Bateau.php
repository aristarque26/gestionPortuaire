<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bateau extends Model
{
    use HasFactory;

    protected $table = 'bateaux';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nom',
        'capacite_totale',
        'type',
        'immatriculation',
        'capacite_passager',
        'capacite_cargaison',
        'statut'
    ];

    /**
     * Relation avec les voyages
     * Un bateau peut avoir plusieurs voyages
     */
    public function voyages()
    {
        return $this->hasMany(Voyage::class, 'idbateau');
    }

    /**
     * Relation avec les pavillons
     * Un bateau peut avoir plusieurs pavillons
     */
    public function pavillons()
    {
        return $this->hasMany(Pavillon::class, 'idbateau');
    }

    /**
     * Relation avec les quais (via la table appartenir)
     * Un bateau peut être assigné à plusieurs quais
     */
    public function quais()
    {
        return $this->belongsToMany(Quai::class, 'appartenir', 'idbateau', 'idquai')
                    ->withTimestamps();
    }

    /**
     * Relation avec les réservations (via les voyages)
     * Un bateau a plusieurs réservations à travers ses voyages
     */
    public function reservations()
    {
        return $this->hasManyThrough(
            Reservation::class,
            Voyage::class,
            'idbateau',  // Clé étrangère sur voyages
            'idvoyage',  // Clé étrangère sur reservations
            'id',        // Clé locale sur bateaux
            'id'         // Clé locale sur voyages
        );
    }

    /**
     * Vérifier si le bateau est en service
     */
    public function isEnService(): bool
    {
        return $this->statut === 'en_service';
    }

    /**
     * Vérifier si le bateau est en maintenance
     */
    public function isEnMaintenance(): bool
    {
        return $this->statut === 'en_maintenance';
    }

    /**
     * Vérifier si le bateau est hors service
     */
    public function isHorsService(): bool
    {
        return $this->statut === 'hors_service';
    }

    /**
     * Obtenir le nombre total de places
     */
    public function getCapaciteTotaleAttribute(): int
    {
        return $this->capacite_passager + $this->capacite_cargaison;
    }

    /**
     * Scope pour les bateaux en service
     */
    public function scopeEnService($query)
    {
        return $query->where('statut', 'en_service');
    }

    /**
     * Scope pour les bateaux en maintenance
     */
    public function scopeEnMaintenance($query)
    {
        return $query->where('statut', 'en_maintenance');
    }

    /**
     * Scope pour les bateaux hors service
     */
    public function scopeHorsService($query)
    {
        return $query->where('statut', 'hors_service');
    }

    /**
     * Scope pour les bateaux par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Obtenir le nombre total de réservations pour ce bateau
     */
    public function getTotalReservationsAttribute(): int
    {
        return $this->reservations()->count();
    }

    /**
     * Obtenir le chiffre d'affaires total pour ce bateau
     */
    public function getTotalCAttribute(): float
    {
        return $this->reservations()->sum('prix_total') ?? 0;
    }

    /**
     * Obtenir le taux d'occupation du bateau
     */
    public function getTauxOccupationAttribute(): float
    {
        if ($this->capacite_totale == 0) {
            return 0;
        }
        $reservations = $this->reservations()->count();
        return round(($reservations / $this->capacite_totale) * 100, 2);
    }
}