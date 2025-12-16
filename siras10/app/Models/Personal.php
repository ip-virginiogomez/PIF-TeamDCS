<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Personal extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'personal';

    protected $primaryKey = 'idPersonal';

    public $timestamps = false;

    protected $fillable = [
        'idCentroSalud',
        'runUsuario',
        'fechaCreacion',
        'fechaInicio',
        'fechaFin',
    ];

    // Relación inversa con CentroSalud
    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class, 'idCentroSalud', 'idCentroSalud');
    }

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'runUsuario', 'runUsuario');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
