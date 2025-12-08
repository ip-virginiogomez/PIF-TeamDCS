<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Periodo extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
