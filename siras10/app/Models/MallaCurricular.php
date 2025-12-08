<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallaCurricular extends Model
{
    use SoftDeletes;
    protected $table = 'malla_curricular';

    protected $primaryKey = 'idMallaCurricular';

    protected $fillable = [
        'anio',
        'fechaCreacion',
    ];

    // Relación con las mallas específicas por sede
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'idMalla', 'idMalla');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Método estático para obtener o crear un año
    public static function obtenerOCrearAnio($anio)
    {
        return self::firstOrCreate(['anio' => $anio]);
    }

    // Obtener años disponibles para selector
    public static function getAniosDisponibles($limite = 5)
    {
        $anioActual = now()->year;
        $anios = [];

        // Años: actual - 2, actual - 1, actual, actual + 1, actual + 2
        for ($i = $anioActual - 2; $i <= $anioActual + 2; $i++) {
            $anios[] = $i;
        }

        return $anios;
    }
}
