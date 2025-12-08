<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CupoOfertaHorario extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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
