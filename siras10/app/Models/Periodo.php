<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $table = 'Periodo';

    protected $primaryKey = 'idPeriodo';

    public $timestamps = false;

    protected $fillable = [
        'Año',
        'fechaCreacion',
        'fechaInicio',
        'fechaFin',
    ];

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idPeriodo', 'idPeriodo');
    }
}
