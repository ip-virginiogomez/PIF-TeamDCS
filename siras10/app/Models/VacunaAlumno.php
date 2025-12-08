<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VacunaAlumno extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'alumno_vacuna';

    protected $primaryKey = 'idAlumnoVacuna';

    public $timestamps = false;

    protected $fillable = [
        'documento',
        'fechaSubida',
        'idEstadoVacuna',
        'runAlumno',
        'idTipoVacuna',
    ];

    // Relación inversa con EstadoVacuna
    public function estadoVacuna()
    {
        return $this->belongsTo(EstadoVacuna::class, 'idEstadoVacuna', 'idEstadoVacuna');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relación inversa con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'runAlumno', 'runAlumno');
    }

    // Relación inversa con TipoVacuna
    public function tipoVacuna()
    {
        return $this->belongsTo(TipoVacuna::class, 'idTipoVacuna', 'idTipoVacuna');
    }
}
