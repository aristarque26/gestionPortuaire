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
}