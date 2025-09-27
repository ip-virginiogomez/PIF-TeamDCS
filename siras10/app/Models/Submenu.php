<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'Submenu';

    protected $primaryKey = 'idSubmenu';

    public $timestamps = false;

    protected $fillable = [
        'nombreSubmenu',
        'idMenu',
    ];

    // Relación inversa con Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idMenu', 'idMenu');
    }

    // Relación uno a muchos con Permisos
    public function permisos()
    {
        return $this->hasMany(Permisos::class, 'idSubmenu', 'idSubmenu');
    }
}
