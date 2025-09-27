<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroFormador extends Model
{
    use HasFactory;

    protected $table = 'centro_formador';

    protected $primaryKey = 'idCentroFormador';

    public $timestamps = false;

    protected $fillable = [
        'nombreCentroFormador',
        'idTipoCentroFormador',
        'fechaCreacion',
    ];

    // Relación inversa con TipoCentroFormador
    public function tipoCentroFormador()
    {
        return $this->belongsTo(TipoCentroFormador::class, 'idTipoCentroFormador', 'idTipoCentroFormador');
    }

    // Relación uno a muchos con CoordinadorCampoClinico
    public function coordinadorCampoClinicos()
    {
        return $this->hasMany(CoordinadorCampoClinico::class, 'idCentroFormador', 'idCentroFormador');
    }

    // Relación uno a muchos con Sede
    public function sedes()
    {
        return $this->hasMany(Sede::class, 'idCentroFormador', 'idCentroFormador');
    }

    // Relación uno a muchos con Convenio
    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'idCentroFormador', 'idCentroFormador');
    }
}
