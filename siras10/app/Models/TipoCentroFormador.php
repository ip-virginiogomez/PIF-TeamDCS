<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCentroFormador extends Model
{
    use HasFactory;

    protected $table = 'TipoCentroFormador';

    protected $primaryKey = 'idTipoCentroFormador';

    public $timestamps = false;

    protected $fillable = [
        'nombreTipo',
        'fechaCreacion',
        'acronimo',
    ];

    // Relación uno a muchos con CentroFormador
    public function centroFormadores()
    {
        return $this->hasMany(CentroFormador::class, 'idTipoCentroFormador', 'idTipoCentroFormador');
    }
}
