<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PermisosRol extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'permisos_rol';

    protected $primaryKey = 'idPermisosRol';

    public $timestamps = false;

    protected $fillable = [
        'idPermisos',
        'idRol',
        'idEstadoPermisos',
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
    public function estadoPermisos()
    {
        return $this->belongsTo(EstadoPermisos::class, 'idEstadoPermisos', 'idEstadoPermisos');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
