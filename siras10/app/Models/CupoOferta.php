<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupoOferta extends Model
{
    use HasFactory;

    protected $table = 'CupoOferta';

    protected $primaryKey = 'idCupoOferta';

    public $timestamps = false;

    protected $fillable = [
        'idPeriodo',
        'idUnidadClinica',
        'idTipoPractica',
        'idCarrera',
        'cantCupos',
        'fechaEntrada',
        'fechaSalida',
        'horaEntrada',
        'horaSalida',
        'fechaCreacion',
    ];

    // Relación inversa con Periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'idPeriodo', 'idPeriodo');
    }

    // Relación inversa con UnidadClinica
    public function unidadClinica()
    {
        return $this->belongsTo(UnidadClinica::class, 'idUnidadClinica', 'idUnidadClinica');
    }

    // Relación inversa con TipoPractica
    public function tipoPractica()
    {
        return $this->belongsTo(TipoPractica::class, 'idTipoPractica', 'idTipoPractica');
    }

    // Relación inversa con Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera', 'idCarrera');
    }

    // Relación uno a muchos con CupoDistribucion
    public function cupoDistribuciones()
    {
        return $this->hasMany(CupoDistribucion::class, 'idCupoOferta', 'idCupoOferta');
    }
}
