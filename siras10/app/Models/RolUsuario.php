<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RolUsuario extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'rol_usuario';

    protected $primaryKey = 'idRolUsuario';

    public $timestamps = false;

    protected $fillable = [
        'idRol',
        'runUsuario',
    ];

    // Relación inversa con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }
}
