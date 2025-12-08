<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Permisos extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'permisos';

    protected $primaryKey = 'idPermisos';

    public $timestamps = false;

    protected $fillable = [
        'nombrePermisos',
        'fechaCreacion',
        'decripcion',
    ];

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'idPermisos', 'idPermisos');
    }

    protected static function booted()
    {
        static::deleted(function ($permiso) {
            $permiso->permisosRoles()->each(function ($permisoRol) {
                $permisoRol->delete();
            });
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
