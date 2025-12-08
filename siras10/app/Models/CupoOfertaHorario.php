<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CupoOfertaHorario extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'cupo_oferta_horarios';

    protected $fillable = [
        'idCupoOferta',
        'diaSemana',
        'horaEntrada',
        'horaSalida',
    ];

    public function cupoOferta()
    {
        return $this->belongsTo(CupoOferta::class, 'idCupoOferta', 'idCupoOferta');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
