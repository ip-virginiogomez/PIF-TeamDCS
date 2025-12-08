<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CentroSalud extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'centro_salud';

    protected $primaryKey = 'idCentroSalud';

    public $timestamps = false;

    protected $fillable = [
        'direccion',
        'idCiudad',
        'idTipoCentroSalud',
        'nombreCentro',
        'director',
        'correoDirector',
    ];

    // Relación inversa con Ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'idCiudad', 'idCiudad');
    }

    // Relación inversa con TipoCentroSalud
    public function tipoCentroSalud()
    {
        return $this->belongsTo(TipoCentroSalud::class, 'idTipoCentroSalud', 'idTipoCentroSalud');
    }

    // Relación uno a muchos con Personal
    public function personal()
    {
        return $this->hasMany(Personal::class, 'idCentroSalud', 'idCentroSalud');
    }

    // Relación uno a muchos con UnidadClinica
    public function unidadClinicas()
    {
        return $this->hasMany(UnidadClinica::class, 'idCentroSalud', 'idCentroSalud');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
