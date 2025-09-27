<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVacuna extends Model
{
    use HasFactory;

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
}
