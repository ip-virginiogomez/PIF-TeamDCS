<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;

    protected $table = 'Permisos';
    protected $primaryKey = 'idPermisos';
    public $timestamps = false;

    protected $fillable = [
        'nombrePermisos',
        'idSubmenu',
        'fechaCreacion',
        'decripcion',
    ];

    // Relación inversa con Submenu
    public function submenu()
    {
        return $this->belongsTo(Submenu::class, 'idSubmenu', 'idSubmenu');
    }

    // Relación uno a muchos con PermisosRol
    public function permisosRoles()
    {
        return $this->hasMany(PermisosRol::class, 'idPermisos', 'idPermisos');
    }
}