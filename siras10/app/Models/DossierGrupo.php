<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierGrupo extends Model
{
    use HasFactory;

    protected $table = 'dossier_grupo';

    protected $primaryKey = 'idDossierGrupo';

    public $timestamps = false;

    protected $fillable = [
        'runAlumno',
        'idGrupo',
        'fechaCreacion',
    ];

    // Relación inversa con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'runAlumno', 'runAlumno');
    }

    // Relación inversa con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'idGrupo', 'idGrupo');
    }
}
