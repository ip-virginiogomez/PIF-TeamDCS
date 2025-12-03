<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignatura';

    protected $primaryKey = 'idAsignatura';

    public $timestamps = false;

    protected $fillable = [
        'nombreAsignatura',
        'idTipoPractica',
        'idSedeCarrera',
        'fechaCreacion',
        'codAsignatura',
        'Semestre',
    ];

    // Relación inversa con TipoPractica
    public function tipoPractica()
    {
        return $this->belongsTo(TipoPractica::class, 'idTipoPractica', 'idTipoPractica');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Programa
    public function programas()
    {
        return $this->hasMany(Programa::class, 'idAsignatura', 'idAsignatura');
    }

    // Relación para obtener el programa más reciente (vigente)
    public function programa()
    {
        return $this->hasOne(Programa::class, 'idAsignatura', 'idAsignatura')
            ->latestOfMany('fechaSubida');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idAsignatura', 'idAsignatura');
    }
}
