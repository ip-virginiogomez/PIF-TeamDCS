<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersonalSalud extends Model
{
    use HasFactory;

    protected $table = 'tipo_personal_salud';

    protected $primaryKey = 'idTipoPersonalSalud';

    public $timestamps = false;

    protected $fillable = [
        'cargo',
        'descripcion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con Usuario
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'idTipoPersonalSalud', 'idTipoPersonalSalud');
    }
}
