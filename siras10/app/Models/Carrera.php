<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'Carrera';
    protected $primaryKey = 'idCarrera';
    public $timestamps = false;

    protected $fillable = [
        'nombreCarrera',
        'fechaCreacion',
    ];

    // Relación uno a muchos con SedeCarrera
    public function sedeCarreras()
    {
        return $this->hasMany(SedeCarrera::class, 'idCarrera', 'idCarrera');
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idCarrera', 'idCarrera');
    }
}