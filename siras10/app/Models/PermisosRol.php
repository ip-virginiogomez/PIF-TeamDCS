<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisosRol extends Model
{
    use HasFactory;

    protected $table = 'permisos_rol';

    protected $primaryKey = 'idPermisosRol';

    public $timestamps = false;

    protected $fillable = [
        'idPermisos',
        'idRol',
        'idEstadoPermisos',
        'runUsuario',
    ];

    // Relación inversa con Permisos
    public function permiso()
    {
        return $this->belongsTo(Permisos::class, 'idPermisos', 'idPermisos');
    }

    // Relación inversa con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    // Relación inversa con EstadoPermisos
    public function estadoPermiso()
    {
        return $this->belongsTo(EstadoPermisos::class, 'idEstadoPermisos', 'idEstadoPermisos');
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }
}
