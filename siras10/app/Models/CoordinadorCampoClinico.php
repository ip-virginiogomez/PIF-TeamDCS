<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CoordinadorCampoClinico extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'coordinador_campo_clinico';

    protected $primaryKey = 'idCoordinador';

    public $timestamps = false;

    protected $fillable = [
        'idCentroFormador',
        'runUsuario',
        'fechaCreacion',
        'fechaInicio',
        'fechaFin',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
