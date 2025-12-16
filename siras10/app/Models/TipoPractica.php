<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TipoPractica extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'tipo_practica';

    protected $primaryKey = 'idTipoPractica';

    public $timestamps = false;

    protected $fillable = [
        'nombrePractica',
        'fechaCreacion',
    ];

    // Relación uno a muchos con Asignatura
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'idTipoPractica', 'idTipoPractica');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idTipoPractica', 'idTipoPractica');
    }

    protected static function booted()
    {
        static::deleted(function ($tipoPractica) {
            $tipoPractica->asignaturas()->each(function ($asignatura) {
                $asignatura->delete();
            });
            $tipoPractica->cupoOfertas()->each(function ($cupoOferta) {
                $cupoOferta->delete();
            });
        });
    }
}
