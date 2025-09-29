<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPermisos extends Model
{
    use HasFactory;

    protected $table = 'estado_permisos';

    protected $primaryKey = 'idEstadoPermisos';

    public $timestamps = false;

    protected $fillable = [
        'nombreEstado',
        'descripcion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'idEstadoPermisos', 'idEstadoPermisos');
    }
}
