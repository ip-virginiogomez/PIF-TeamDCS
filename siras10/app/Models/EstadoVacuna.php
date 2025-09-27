<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoVacuna extends Model
{
    use HasFactory;

    protected $table = 'EstadoVacuna';

    protected $primaryKey = 'idEstadoVacuna';

    public $timestamps = false;

    protected $fillable = [
        'nombreEstado',
        'descripcion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con VacunaAlumno
    public function vacunaAlumnos()
    {
        return $this->hasMany(VacunaAlumno::class, 'idEstadoVacuna', 'idEstadoVacuna');
    }

    // Relación uno a muchos con DocenteVacuna
    public function docenteVacunas()
    {
        return $this->hasMany(DocenteVacuna::class, 'idEstadoVacuna', 'idEstadoVacuna');
    }
}
