<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CupoDemanda extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'cupo_demanda';

    protected $primaryKey = 'idDemandaCupo';

    protected $fillable = [
        'idPeriodo',
        'idSedeCarrera',
        'cuposSolicitados',
    ];

    // Relación inversa con Periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'idPeriodo', 'idPeriodo');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con CupoDistribucion
    public function cupoDistribuciones()
    {
        return $this->hasMany(CupoDistribucion::class, 'idDemandaCupo', 'idDemandaCupo');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);

        static::deleted(function ($cupoDemanda) {
            $cupoDemanda->cupoDistribuciones()->each(function ($cupoDistribucion) {
                $cupoDistribucion->delete();
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
