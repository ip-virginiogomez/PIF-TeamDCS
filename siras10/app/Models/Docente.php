<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Docente extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'docente';

    protected $primaryKey = 'runDocente';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'runDocente',
        'nombresDocente',
        'apellidoPaterno',
        'apellidoMaterno',
        'correo',
        'fechaCreacion',
        'fechaNacto',
        'profesion',
        'foto',
        'certSuperInt',
        'curriculum',
        'certRCP',
        'certIAAS',
        'acuerdo',
    ];

    // Relación uno a muchos con DocenteCarrera
    public function docenteCarreras()
    {
        return $this->hasMany(DocenteCarrera::class, 'runDocente', 'runDocente');
    }

    // Relación uno a muchos con DocenteVacuna
    public function docenteVacunas()
    {
        return $this->hasMany(DocenteVacuna::class, 'runDocente', 'runDocente');
    }

    public function sedesCarreras()
    {
        return $this->belongsToMany(
            SedeCarrera::class,
            'docente_carrera',
            'runDocente',
            'idSedeCarrera'
        );
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
