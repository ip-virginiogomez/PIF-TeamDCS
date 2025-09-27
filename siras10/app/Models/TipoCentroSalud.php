<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCentroSalud extends Model
{
    use HasFactory;

    protected $table = 'TipoCentroSalud';

    protected $primaryKey = 'idTipoCentroSalud';

    public $timestamps = false;

    protected $fillable = [
        'nombreTipo',
        'fechaCreacion',
        'acronimo',
    ];

    // Relación uno a muchos con CentroSalud
    public function centroSaluds()
    {
        return $this->hasMany(CentroSalud::class, 'idTipoCentroSalud', 'idTipoCentroSalud');
    }
}
