<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadClinica extends Model
{
    use HasFactory;

    protected $table = 'UnidadClinica';
    protected $primaryKey = 'idUnidadClinica';
    public $timestamps = false;

    protected $fillable = [
        'idCentroSalud',
        'nombreUnidad',
        'fechaCreacion',
    ];

    // Relación inversa con CentroSalud
    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class, 'idCentroSalud', 'idCentroSalud');
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idUnidadClinica', 'idUnidadClinica');
    }
}