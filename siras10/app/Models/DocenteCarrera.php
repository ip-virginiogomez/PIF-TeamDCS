<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteCarrera extends Model
{
    use HasFactory;

    protected $table = 'docente_carrera';

    protected $primaryKey = 'idDocenteCarrera';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'idDocenteCarrera',
        'runDocente',
        'idSedeCarrera',
    ];

    // Relación inversa con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'runDocente', 'runDocente');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Grupo
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idDocenteCarrera', 'idDocenteCarrera');
    }
}
