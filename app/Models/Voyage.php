<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function bateau()
    {
        return $this->belongsTo(Bateau::class, 'idbateau');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idvoyage');
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class, 'idvoyage');
    }

    // ✅ Nombre de places déjà réservées pour ce voyage
    public function placesReservees()
    {
        return $this->reservations()->count();
    }

    // ✅ Places disponibles (capacité bateau - places réservées)
    public function placesDisponibles()
    {
        $capacite = $this->bateau->capacite_passager ?? 0;
        return max(0, $capacite - $this->placesReservees());
    }
}