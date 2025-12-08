<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UnidadClinica extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'unidad_clinica';

    protected $primaryKey = 'idUnidadClinica';

    public $timestamps = false;

    protected $fillable = [
        'idCentroSalud',
        'nombreUnidad',
        'fechaCreacion',
    ];

    // Relación inversa con CentroSalud
    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class, 'idCentroSalud', 'idCentroSalud');
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idUnidadClinica', 'idUnidadClinica');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
