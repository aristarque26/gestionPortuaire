<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contiendra extends Model
{
    use HasFactory;

    protected $table = 'contiendra';
    public $timestamps = true;

    protected $fillable = [
        'idpavillon',
        'idtrajet',
        'prix'
    ];

    public function pavillon()
    {
        return $this->belongsTo(Pavillon::class, 'idpavillon');
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'idtrajet');
    }
}