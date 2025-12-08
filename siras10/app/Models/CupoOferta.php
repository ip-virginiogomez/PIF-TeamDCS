<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CupoOferta extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'cupo_oferta';

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

    // Relación uno a muchos con Horarios
    public function horarios()
    {
        return $this->hasMany(CupoOfertaHorario::class, 'idCupoOferta', 'idCupoOferta');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
