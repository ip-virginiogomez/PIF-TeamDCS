<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupo';

    protected $primaryKey = 'idGrupo';

    public $timestamps = false;

    protected $fillable = [
        'idCupoDistribucion',
        'idDocenteCarrera',
        'idAsignatura',
        'fechaCreacion',
        'nombreGrupo',
    ];

    // Relación inversa con CupoDistribucion
    public function cupoDistribucion()
    {
        return $this->belongsTo(CupoDistribucion::class, 'idCupoDistribucion', 'idCupoDistribucion');
    }

    // Relación inversa con DocenteCarrera
    public function docenteCarrera()
    {
        return $this->belongsTo(DocenteCarrera::class, 'idDocenteCarrera', 'idDocenteCarrera');
    }

    // Relación uno a muchos con DossierGrupo
    public function dossierGrupos()
    {
        return $this->hasMany(DossierGrupo::class, 'idGrupo', 'idGrupo');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'idAsignatura', 'idAsignatura');
    }

    public function alumnos()
    {
        return $this->belongsToMany(
            Alumno::class,
            'dossier_grupo',
            'idGrupo',
            'runAlumno'
        );
    }
}
