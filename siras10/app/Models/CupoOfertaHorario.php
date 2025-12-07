<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupoOfertaHorario extends Model
{
    use HasFactory;

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
}
