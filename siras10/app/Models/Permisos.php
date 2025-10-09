<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $primaryKey = 'idPermisos';

    public $timestamps = false;

    protected $fillable = [
        'nombrePermisos',
        'fechaCreacion',
        'decripcion',
    ];

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'idPermisos', 'idPermisos');
    }
}
