<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Periodo extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'periodo';

    protected $primaryKey = 'idPeriodo';

    public $timestamps = false;

    protected $fillable = [
        'Año',
        'fechaCreacion',
        'fechaInicio',
        'fechaFin',
    ];

    // Relación uno a muchos con CupoOferta
    public function cuposOferta()
    {
        return $this->hasMany(CupoOferta::class, 'idPeriodo', 'idPeriodo');
    }

    // Relación uno a muchos con CupoDemanda
    public function cupoDemandas()
    {
        return $this->hasMany(CupoDemanda::class, 'idPeriodo', 'idPeriodo');
    }

    protected static function booted()
    {
        static::deleted(function ($periodo) {
            $periodo->cuposOferta()->each(function ($cupoOferta) {
                $cupoOferta->delete();
            });
            $periodo->cupoDemandas()->each(function ($cupoDemanda) {
                $cupoDemanda->delete();
            });
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
