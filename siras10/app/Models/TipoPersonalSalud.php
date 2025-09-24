<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersonalSalud extends Model
{
    use HasFactory;

    protected $table = 'TipoPersonalSalud';

    protected $primaryKey = 'idTipoPersonalSalud';

    public $timestamps = false;

    protected $fillable = [
        'cargo',
        'descipcion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con Usuario
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'idTipoPersonalSalud', 'idTipoPersonalSalud');
    }
}
