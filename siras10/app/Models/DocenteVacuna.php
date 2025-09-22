<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteVacuna extends Model
{
    use HasFactory;

    protected $table = 'DocenteVacuna';
    protected $primaryKey = 'idDocenteVacuna';
    public $timestamps = false;

    protected $fillable = [
        'documento',
        'fechaSubida',
        'idEstadoVacuna',
        'idTipoVacuna',
        'runDocente',
    ];

    // Relación inversa con EstadoVacuna
    public function estadoVacuna()
    {
        return $this->belongsTo(EstadoVacuna::class, 'idEstadoVacuna', 'idEstadoVacuna');
    }

    // Relación inversa con TipoVacuna
    public function tipoVacuna()
    {
        return $this->belongsTo(TipoVacuna::class, 'idTipoVacuna', 'idTipoVacuna');
    }

    // Relación inversa con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'runDocente', 'runDocente');
    }
}