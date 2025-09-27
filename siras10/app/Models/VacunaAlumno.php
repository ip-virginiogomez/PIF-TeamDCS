<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacunaAlumno extends Model
{
    use HasFactory;

    protected $table = 'VacunaAlumno';

    protected $primaryKey = 'idVacunaAlumno';

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
