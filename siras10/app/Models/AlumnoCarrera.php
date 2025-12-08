<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AlumnoCarrera extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'alumno_carrera';

    protected $primaryKey = 'idAlumnoCarrera';

    public $timestamps = false;

    protected $fillable = [
        'runAlumno',
        'idSedeCarrera',
    ];

    // Relación inversa con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'runAlumno', 'runAlumno');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
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
