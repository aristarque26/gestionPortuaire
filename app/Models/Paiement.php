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
        'date_paiement' => 'datetime'
    ];

    // ✅ Statuts autorisés pour la colonne 'statut'
    const STATUTS = ['paye', 'en_attente', 'echoue', 'rembourse'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }
}