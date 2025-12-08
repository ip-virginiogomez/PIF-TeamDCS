<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Programa extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'programa';

    protected $primaryKey = 'idPrograma';

    public $timestamps = false;

    protected $fillable = [
        'idAsignatura',
        'documento',
        'fechaSubida',
    ];

    protected $casts = [
        'fechaSubida' => 'datetime',
    ];

    // Relación inversa con Asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'idAsignatura', 'idAsignatura');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
