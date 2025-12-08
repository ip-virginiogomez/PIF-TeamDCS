<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Alumno extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'alumno';

    protected $primaryKey = 'runAlumno';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'runAlumno',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'fechaNacto',
        'foto',
        'acuerdo',
        'fechaCreacion',
        'correo',
    ];

    // Relación uno a muchos con AlumnoCarrera
    public function alumnoCarreras()
    {
        return $this->hasMany(AlumnoCarrera::class, 'runAlumno', 'runAlumno');
    }

    // Relación uno a muchos con VacunaAlumno
    public function vacunas()
    {
        return $this->hasMany(VacunaAlumno::class, 'runAlumno', 'runAlumno');
    }

    // Relación uno a muchos con DossierGrupo
    public function dossierGrupos()
    {
        return $this->hasMany(DossierGrupo::class, 'runAlumno', 'runAlumno');
    }

    public function sedesCarreras()
    {
        return $this->belongsToMany(
            SedeCarrera::class,
            'alumno_carrera',
            'runAlumno',
            'idSedeCarrera'
        );
    }

    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'dossier_grupo',
            'runAlumno',
            'idGrupo'
        );
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
