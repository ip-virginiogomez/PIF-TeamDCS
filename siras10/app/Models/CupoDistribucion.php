<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupoDistribucion extends Model
{
    use HasFactory;

    protected $table = 'CupoDistribucion';

    protected $primaryKey = 'idCupoDistribucion';

    public $timestamps = false;

    protected $fillable = [
        'idCupoOferta',
        'idSedeCarrera',
        'cantCupos',
        'fechaCreacion',
    ];

    // Relación inversa con CupoOferta
    public function cupoOferta()
    {
        return $this->belongsTo(CupoOferta::class, 'idCupoOferta', 'idCupoOferta');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Grupo
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idCupoDistribucion', 'idCupoDistribucion');
    }
}
