<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TipoPersonalSalud extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tipo_personal_salud';

    protected $primaryKey = 'idTipoPersonalSalud';

    public $timestamps = false;

    protected $fillable = [
        'cargo',
        'descripcion',
        'fechaCreacion',
    ];

    // Relación uno a muchos con Usuario
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'idTipoPersonalSalud', 'idTipoPersonalSalud');
    }

    // Relación uno a muchos con Personal
    public function personal()
    {
        return $this->hasMany(Personal::class, 'idTipoPersonalSalud', 'idTipoPersonalSalud');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
