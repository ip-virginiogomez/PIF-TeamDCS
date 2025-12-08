<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipoPractica extends Model
{
    use HasFactory, LogsActivity;

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
}
