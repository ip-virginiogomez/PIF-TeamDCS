<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programa';

    protected $primaryKey = 'idPrograma';

    public $timestamps = false;

    protected $fillable = [
        'idAsignatura',
        'doc',
        'fechaSubida',
    ];

    // Relación inversa con Asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'idAsignatura', 'idAsignatura');
    }
}
