<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinadorCampoClinico extends Model
{
    use HasFactory;

    protected $table = 'CoordinadorCampoClinico';
    protected $primaryKey = 'idCoordinador';
    public $timestamps = false;

    protected $fillable = [
        'idCentroFormador',
        'runUsuario',
        'fechaCreacion',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }
}