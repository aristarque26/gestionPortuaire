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
    
    // ========== RELATIONS ==========
    public function client()
    {
        return $this->hasOne(Client::class, 'idutilisateur');
    }

    public function personnel()
    {
        return $this->hasOne(Personnel::class);
    }
    
    // ========== MÉTHODES RÔLES ==========
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isClient()
    {
        return $this->role === 'client';
    }
    
    public function isPersonnel()
    {
        return $this->role === 'personnel';
    }
    
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrateur',
            'client' => 'Client',
            'personnel' => 'Agent portuaire',
            default => 'Inconnu'
        };
    }
    
    // ========== ACCESSOIRES ==========
    public function getNomCompletAttribute()
    {
        return trim($this->prenom . ' ' . $this->name);
    }
    
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }
}