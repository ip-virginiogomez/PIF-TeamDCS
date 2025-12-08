<?php

namespace App\Models;

use App\Models\Scopes\CentroFormadorScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Convenio extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'convenio';

    protected $primaryKey = 'idConvenio';

    public $timestamps = false;

    protected $fillable = [
        'documento',
        'idCentroFormador',
        'fechaSubida',
        'fechaInicio',
        'fechaFin',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
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
