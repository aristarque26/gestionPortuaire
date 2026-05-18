<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $table = 'ports';
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'nom',
        'localisation',
        'ville',
        'statut'
    ];

    /**
     * Relation avec les quais
     */
    public function quais()
    {
        return $this->hasMany(Quai::class, 'idport');
    }
}