<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MallaSedeCarrera extends Model
{
    use HasFactory;

    protected $table = 'MallaSedeCarrera';

    protected $primaryKey = 'idMallaSedeCarrera';

    public $timestamps = false;

    protected $fillable = [
        'fechaSubida',
        'doc',
        'anio',
        'idMallaCurricular',
        'idSedeCarrera',
    ];

    // Relación inversa con MallaCurricular
    public function mallaCurricular()
    {
        return $this->belongsTo(MallaCurricular::class, 'idMallaCurricular', 'idMallaCurricular');
    }

    // Relación inversa con SedeCarrera
    public function sedeCarrera()
    {
        return $this->belongsTo(SedeCarrera::class, 'idSedeCarrera', 'idSedeCarrera');
    }
}
