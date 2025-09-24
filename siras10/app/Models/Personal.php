<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'Personal';

    protected $primaryKey = 'idPersonal';

    public $timestamps = false;

    protected $fillable = [
        'idCentroSalud',
        'runUsuario',
        'fechaCreacion',
    ];

    // Relación inversa con CentroSalud
    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class, 'idCentroSalud', 'idCentroSalud');
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }
}
