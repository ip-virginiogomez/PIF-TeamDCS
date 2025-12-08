<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Permisos extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
