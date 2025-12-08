<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Carrera extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'carrera';

    protected $primaryKey = 'idCarrera';

    public $timestamps = false;

    protected $fillable = [
        'nombreCarrera',
        'fechaCreacion',
    ];

    // Relación uno a muchos con SedeCarrera
    public function sedeCarreras()
    {
        return $this->hasMany(SedeCarrera::class, 'idCarrera', 'idCarrera');
    }

    // Relación uno a muchos con CupoOferta
    public function cupoOfertas()
    {
        return $this->hasMany(CupoOferta::class, 'idCarrera', 'idCarrera');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
