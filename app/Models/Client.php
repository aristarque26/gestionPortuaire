<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'nationalite',
        'genre',
        'photo',
        'date_inscription',
        'statut',
        'idutilisateur'
    ];

    protected $casts = [
        'date_inscription' => 'datetime'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idutilisateur');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idclient');
    }
    
}