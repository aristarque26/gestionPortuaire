<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conceder extends Model
{
    use HasFactory;

    protected $table = 'conceder';
    public $timestamps = true;

    protected $fillable = [
        'idport',
        'idtrajet',
        'ordre_etape',
        'role_port'
    ];

    public function port()
    {
        return $this->belongsTo(Port::class, 'idport');
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'idtrajet');
    }
}