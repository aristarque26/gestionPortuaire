<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
        'statut',
        'photo'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
        'statut' => 'string'
    ];
    
    // Méthodes pour les rôles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isPersonnel()
    {
        return $this->role === 'personnel';
    }
    
    // Nom complet
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->name;
    }
    
    // Accesseur pour l'URL de la photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }
    public function client()
{
    return $this->hasOne(Client::class, 'idutilisateur');
}
}