<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DocenteCarrera extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'docente_carrera';

    protected $primaryKey = 'idDocenteCarrera';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'idDocenteCarrera',
        'runDocente',
        'idSedeCarrera',
    ];

    // Relación inversa con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'runDocente', 'runDocente');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }

    // Relación uno a muchos con Grupo
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'idDocenteCarrera', 'idDocenteCarrera');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);

        static::deleted(function ($docenteCarrera) {
            $docenteCarrera->grupos()->each(function ($grupo) {
                $grupo->delete();
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
