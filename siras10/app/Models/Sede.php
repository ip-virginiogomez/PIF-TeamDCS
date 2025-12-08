<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sede extends Model
{
    use HasFactory, LogsActivity;

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

    /**
     * Relación uno a muchos inversa con CentroFormador.
     * Una Sede pertenece a un Centro Formador.
     */
    public function centroFormador(): BelongsTo
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    /**
     * Relación uno a muchos con la tabla pivote SedeCarrera.
     * Útil cuando necesitas acceder a los registros intermedios.
     */
    public function sedeCarreras(): HasMany
    {
        return $this->hasMany(SedeCarrera::class, 'idSede', 'idSede');
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
