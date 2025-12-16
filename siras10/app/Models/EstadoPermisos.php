<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EstadoPermisos extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
