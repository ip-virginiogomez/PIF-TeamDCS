<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'Usuario';
    protected $primaryKey = 'runUsuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'runUsuario',
        'nombreUsuario',
        'correo',
        'contrasenia',
        'fechaCreacion',
        'idTipoPersonalSalud',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
    ];

    protected $hidden = [
        'contrasenia',
    ];
    
    // Cambia el nombre del campo de contraseña para la autenticación
    public function getAuthPassword()
    {
        return $this->contrasenia;
    }
    
    // Relación inversa con TipoPersonalSalud
    public function tipoPersonalSalud()
    {
        return $this->belongsTo(TipoPersonalSalud::class, 'idTipoPersonalSalud', 'idTipoPersonalSalud');
    }

    // Relación uno a muchos con CoordinadorCampoClinico
    public function coordinadorCampoClinicos()
    {
        return $this->hasMany(CoordinadorCampoClinico::class, 'runUsuario', 'runUsuario');
    }

    // Relación uno a muchos con Personal
    public function personales()
    {
        return $this->hasMany(Personal::class, 'runUsuario', 'runUsuario');
    }

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'runUsuario', 'runUsuario');
    }
}
