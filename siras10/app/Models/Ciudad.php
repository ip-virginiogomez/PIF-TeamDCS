<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'Ciudad';

    protected $primaryKey = 'idCiudad';

    public $timestamps = false;

    protected $fillable = [
        'nombreCiudad',
        'fechacreacion',
    ];

    // Relación uno a muchos con CentroSalud
    public function centroSaluds()
    {
        return $this->hasMany(CentroSalud::class, 'idCiudad', 'idCiudad');
    }
}
