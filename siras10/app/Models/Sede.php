<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'Sede';

    protected $primaryKey = 'idSede';

    public $timestamps = false;

    protected $fillable = [
        'nombreSede',
        'direccion',
        'idCentroFormador',
        'fechaCreacion',
        'numContacto',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    // Relación uno a muchos con SedeCarrera
    public function sedeCarreras()
    {
        return $this->hasMany(SedeCarrera::class, 'idSede', 'idSede');
    }
}
