<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'sede';

    protected $primaryKey = 'idSede';

    public $timestamps = false;

    protected $fillable = [
        'nombreSede',
        'direccion',
        'idCentroFormador',
        'fechaCreacion',
        'numContacto',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sede) {
            if (empty($sede->fechaCreacion)) {
                $sede->fechaCreacion = Carbon::now()->format('Y-m-d');
            }
        });
    }

    // Relación uno a muchos con SedeCarrera
    public function sedeCarreras()
    {
        return $this->hasMany(SedeCarrera::class, 'idSede', 'idSede');
    }
}
