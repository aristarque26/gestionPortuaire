<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'idpavillon',  // ✅ AJOUTÉ
        'prix_total'    // ✅ AJOUTÉ
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
        'date_embarquement' => 'datetime',
        'date_arrivee' => 'datetime',
        'prix_total' => 'decimal:2' // ✅ AJOUTÉ
    ];

    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'idvoyage');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'idreservation');
    }

    // ✅ RELATION DIRECTE avec un seul pavillon (pour la réservation principale)
    public function pavillon()
    {
        return $this->belongsTo(Pavillon::class, 'idpavillon');
    }

    // ✅ GARDE LA RELATION MANY-TO-MANY existante (si tu l’utilises encore ailleurs)
    public function pavillons()
    {
        return $this->belongsToMany(Pavillon::class, 'reserve', 'idreservation', 'idpavillon')
                    ->withPivot('prix');
    }
}