<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SedeCarrera extends Model
{
    use HasFactory, LogsActivity;

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

    // Relación uno a muchos con CupoDistribucion
    public function cupoDistribuciones()
    {
        return $this->hasMany(CupoDistribucion::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }
}
