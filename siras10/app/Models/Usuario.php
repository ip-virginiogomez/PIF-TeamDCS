<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'runUsuario';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'runUsuario',
        'nombreUsuario',
        'apellidoPaterno',
        'apellidoMaterno',
        'correo',
        'telefono',
        'foto',
        'contrasenia',
        'fechaCreacion',
        'idTipoPersonalSalud',
        'remember_token',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    // Cambia el nombre del campo de contraseña para la autenticación
    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    // Método para obtener el email usado en el reset de contraseña
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // Accessor para que Laravel trate 'correo' como 'email'
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    // Sobrescribir el método de envío de notificación de reset
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
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

    public function esAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function esCoordinador()
    {
        return $this->hasRole('Coordinador Campo Clínico');
    }

    public function esTecnicoRAD()
    {
        return $this->hasRole('Técnico RAD');
    }

    public function centrosFormadores()
    {
        return $this->belongsToMany(
            CentroFormador::class,
            'coordinador_campo_clinico',
            'runUsuario',
            'idCentroFormador'
        )->withPivot(['fechaInicio', 'fechaFin']);
    }

    public function centroSalud()
    {
        return $this->belongsToMany(
            CentroSalud::class,
            'personal',
            'runUsuario',
            'idCentroSalud'
        )->withPivot(['fechaInicio', 'fechaFin']);
    }
}
