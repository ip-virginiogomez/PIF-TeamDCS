<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

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

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }
}
