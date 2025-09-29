<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

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
    public function vacunaAlumnos()
    {
        return $this->hasMany(VacunaAlumno::class, 'runAlumno', 'runAlumno');
    }

    // Relación uno a muchos con DossierGrupo
    public function dossierGrupos()
    {
        return $this->hasMany(DossierGrupo::class, 'runAlumno', 'runAlumno');
    }
}
