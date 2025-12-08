<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipoCentroFormador extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tipo_centro_formador';

    protected $primaryKey = 'idTipoCentroFormador';

    public $timestamps = false;

    protected $fillable = [
        'nombreTipo',
        'fechaCreacion',
    ];

    // Relación uno a muchos con CentroFormador
    public function centrosFormadores()
    {
        return $this->hasMany(CentroFormador::class, 'idTipoCentroFormador', 'idTipoCentroFormador');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
