<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MallaCurricular extends Model
{
    use HasFactory;

    protected $table = 'malla_curricular';

    protected $primaryKey = 'idMallaCurricular';

    public $timestamps = false;

    protected $fillable = [
        'fechaCreacion',
    ];

    // Relación uno a muchos con MallaSedeCarrera
    public function mallaSedeCarreras()
    {
        return $this->hasMany(MallaSedeCarrera::class, 'idMallaCurricular', 'idMallaCurricular');
    }
}
