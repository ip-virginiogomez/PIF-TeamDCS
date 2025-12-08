<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MallaSedeCarrera extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'malla_sede_carrera';

    protected $primaryKey = 'idMallaSedeCarrera';

    public $timestamps = false;

    protected $fillable = [
        'fechaSubida',
        'documento',
        'nombre',
        'idMallaCurricular',
        'idSedeCarrera',
    ];

    protected $casts = [
        'fechaSubida' => 'datetime',
    ];

    // Relación inversa con MallaCurricular
    public function mallaCurricular()
    {
        return $this->belongsTo(MallaCurricular::class, 'idMallaCurricular', 'idMallaCurricular');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }
}
