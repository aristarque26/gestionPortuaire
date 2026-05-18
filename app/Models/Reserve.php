<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;

    protected $table = 'reserve';
    public $timestamps = true;

    protected $fillable = [
        'idreservation',
        'idpavillon',
        'prix'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }

    public function pavillon()
    {
        return $this->belongsTo(Pavillon::class, 'idpavillon');
    }
}