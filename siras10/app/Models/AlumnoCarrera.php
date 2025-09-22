<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoCarrera extends Model
{
    use HasFactory;

    protected $table = 'AlumnoCarrera';
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
}