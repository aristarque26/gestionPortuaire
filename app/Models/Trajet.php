<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $table = 'trajets';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nom',
        'description',
        'date',
        'distance',
        'ordre',
        'idvoyage'
    ];

    protected $casts = [
        'date' => 'datetime',
        'distance' => 'decimal:2'
    ];

    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'idvoyage');
    }

    public function pavillons()
    {
        return $this->belongsToMany(Pavillon::class, 'contiendra', 'idtrajet', 'idpavillon')
                    ->withPivot('prix');
    }

    public function ports()
    {
        return $this->belongsToMany(Port::class, 'conceder', 'idtrajet', 'idport')
                    ->withPivot('ordre_etape', 'role_port');
    }
}