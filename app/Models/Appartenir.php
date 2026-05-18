<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartenir extends Model
{
    use HasFactory;

    protected $table = 'appartenir';
    public $timestamps = true;

    protected $fillable = [
        'idbateau',
        'idquai'
    ];

    public function bateau()
    {
        return $this->belongsTo(Bateau::class, 'idbateau');
    }

    public function quai()
    {
        return $this->belongsTo(Quai::class, 'idquai');
    }
}