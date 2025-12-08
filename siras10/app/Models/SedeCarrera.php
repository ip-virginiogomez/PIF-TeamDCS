<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SedeCarrera extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'sede_carrera';

    protected $primaryKey = 'idSedeCarrera';

    public $timestamps = false;

    protected $fillable = [
        'nombreSedeCarrera',
        'idSede',
        'idCarrera',
        'codigoCarrera',
        'fechaCreacion',
    ];

    // Relación inversa con Sede
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idSede', 'idSede');
    }

    // Relación inversa con Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera', 'idCarrera');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relación uno a muchos con MallaSedeCarrera
    public function mallaSedeCarreras()
    {
        return $this->hasMany(MallaSedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con DocenteCarrera
    public function docenteCarreras()
    {
        return $this->hasMany(DocenteCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con AlumnoCarrera
    public function alumnoCarreras()
    {
        return $this->hasMany(AlumnoCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Asignatura
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con CupoDemanda
    public function cupoDemandas()
    {
        return $this->hasMany(CupoDemanda::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);

        static::deleted(function ($sedeCarrera) {
            $sedeCarrera->asignaturas()->each(function ($asignatura) {
                $asignatura->delete();
            });
            $sedeCarrera->cupoDemandas()->each(function ($cupoDemanda) {
                $cupoDemanda->delete();
            });
            $sedeCarrera->docenteCarreras()->each(function ($docenteCarrera) {
                $docenteCarrera->delete();
            });
            $sedeCarrera->alumnoCarreras()->each(function ($alumnoCarrera) {
                $alumnoCarrera->delete();
            });
            $sedeCarrera->mallaSedeCarreras()->each(function ($mallaSedeCarrera) {
                $mallaSedeCarrera->delete();
            });
        });
    }
}
