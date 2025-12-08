<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rol extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
