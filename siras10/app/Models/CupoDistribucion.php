<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CupoDistribucion extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'cupo_distribucion';

    protected $primaryKey = 'idCupoDistribucion';

    public $timestamps = false;

    protected $fillable = [
        'idCupoOferta',
        'idSedeCarrera',
        'cantCupos',
        'fechaCreacion',
    ];

    // Relación inversa con CupoOferta
    public function cupoOferta()
    {
        return $this->belongsTo(CupoOferta::class, 'idCupoOferta', 'idCupoOferta');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Grupo
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idCupoDistribucion', 'idCupoDistribucion');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
