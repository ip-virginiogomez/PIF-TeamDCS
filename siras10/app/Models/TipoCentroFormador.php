<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TipoCentroFormador extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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
