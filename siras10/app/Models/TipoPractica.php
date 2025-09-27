<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPractica extends Model
{
    use HasFactory;

    protected $table = 'tipo_practica';

    protected $primaryKey = 'idTipoPractica';

    public $timestamps = false;

    protected $fillable = [
        'nombrePractica',
        'fechaCreacion',
    ];

    // Relación uno a muchos con Asignatura
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'idTipoPractica', 'idTipoPractica');
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idTipoPractica', 'idTipoPractica');
    }
}
