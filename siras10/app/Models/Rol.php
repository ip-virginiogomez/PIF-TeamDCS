<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'Rol';

    protected $primaryKey = 'idRol';

    public $timestamps = false;

    protected $fillable = [
        'nombreRol',
        'fechaCreacion',
        'descripcion',
    ];

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'idRol', 'idRol');
    }
}
