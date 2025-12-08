<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DossierGrupo extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
