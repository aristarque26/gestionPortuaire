<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pavillon extends Model
{
    use HasFactory;

    protected $table = 'pavillons';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nom',
        'capacite_max',
        'classe',
        'unite',
        'prix_unitaire',
        'prix_tonne',  // ✅ AJOUTÉ
        'idbateau'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'prix_tonne' => 'decimal:2'  // ✅ AJOUTÉ
    ];

    public function bateau()
    {
        return $this->belongsTo(Bateau::class, 'idbateau');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reserve', 'idpavillon', 'idreservation')
                    ->withPivot('prix');
    }

    public function trajets()
    {
        return $this->belongsToMany(Trajet::class, 'contiendra', 'idpavillon', 'idtrajet')
                    ->withPivot('prix');
    }

    // ✅ Places réservées pour ce pavillon sur un voyage donné
    public function placesReserveesPourVoyage($voyageId)
    {
        return Reservation::where('idvoyage', $voyageId)
            ->where('idpavillon', $this->id)
            ->count();
    }

    // ✅ Places disponibles pour ce pavillon sur un voyage donné
    public function placesDisponiblesPourVoyage($voyageId)
    {
        return max(0, $this->capacite_max - $this->placesReserveesPourVoyage($voyageId));
    }
}