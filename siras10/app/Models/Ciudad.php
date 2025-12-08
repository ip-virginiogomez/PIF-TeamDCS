<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ciudad extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'ciudad';

    protected $primaryKey = 'idCiudad';

    public $timestamps = false;

    protected $fillable = [
        'nombreCiudad',
        'fechacreacion',
    ];

    // Relación uno a muchos con CentroSalud
    public function centroSaluds()
    {
        return $this->hasMany(CentroSalud::class, 'idCiudad', 'idCiudad');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
