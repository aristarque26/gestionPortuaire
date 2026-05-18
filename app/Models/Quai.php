<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quai extends Model
{
    use HasFactory;

    protected $table = 'quais';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nom',
        'capacite',
        'type_quai',
        'statut',
        'numero',
        'idport'
    ];

    public function port()
    {
        return $this->belongsTo(Port::class, 'idport');
    }

    public function bateaux()
    {
        return $this->belongsToMany(Bateau::class, 'appartenir', 'idquai', 'idbateau');
    }
}