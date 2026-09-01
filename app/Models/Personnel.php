<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';

    protected $fillable = [
        'user_id',
        'matricule',
        'poste',
        'service',
        'date_embauche',
        'personnel_role',
        'salaire',
        'statut'  // ✅ Présent
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRoleLabelAttribute()
    {
        return match($this->personnel_role) {
            'superviseur' => 'Superviseur',
            'comptable' => 'Comptable',
            'caissier' => 'Caissier',
            'agent_portuaire' => 'Agent portuaire',
            'gestionnaire' => 'Gestionnaire',
            default => 'Non défini'
        };
    }
}