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
        'idpavillon',
        'prix_total'
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
        'date_embarquement' => 'datetime',
        'date_arrivee' => 'datetime',
        'prix_total' => 'decimal:2'
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

    public function pavillon()
    {
        return $this->belongsTo(Pavillon::class, 'idpavillon');
    }

    public function pavillons()
    {
        return $this->belongsToMany(Pavillon::class, 'reserve', 'idreservation', 'idpavillon')
                    ->withPivot('prix');
    }

    /**
 * Générer un token unique pour le paiement direct
 */
    public function generatePaiementToken()
    {
        return hash_hmac('sha256', $this->id . $this->idclient . $this->created_at, config('app.key'));
    }
    /* Documentation complète de l'application - toutes les fonctionnalités expliquées */
}