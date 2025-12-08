<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipoCentroSalud extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tipo_centro_salud';

    protected $primaryKey = 'idTipoCentroSalud';

    public $timestamps = false;

    protected $fillable = [
        'nombreTipo',
        'fechaCreacion',
        'acronimo',
    ];

    // Relación uno a muchos con CentroSalud
    public function centrosSalud()
    {
        return $this->hasMany(CentroSalud::class, 'idTipoCentroSalud', 'idTipoCentroSalud');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
