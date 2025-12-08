<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rol extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'rol';

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

    protected static function booted()
    {
        static::deleted(function ($rol) {
            $rol->permisosRoles()->each(function ($permisoRol) {
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
