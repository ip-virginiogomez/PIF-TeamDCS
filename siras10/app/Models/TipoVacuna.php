<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TipoVacuna extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'tipo_vacuna';

    protected $primaryKey = 'idTipoVacuna';

    public $timestamps = false;

    protected $fillable = [
        'nombreVacuna',
        'duracion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con VacunaAlumno
    public function vacunaAlumnos()
    {
        return $this->hasMany(VacunaAlumno::class, 'idTipoVacuna', 'idTipoVacuna');
    }

    // Relación uno a muchos con DocenteVacuna
    public function docenteVacunas()
    {
        return $this->hasMany(DocenteVacuna::class, 'idTipoVacuna', 'idTipoVacuna');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
