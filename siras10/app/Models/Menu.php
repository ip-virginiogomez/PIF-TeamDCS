<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'Menu';

    public $timestamps = false;

    protected $primaryKey = 'idMenu';

    protected $fillable = [
        'nombreMenu',
    ];

    // Relación uno a muchos con Submenu
    public function submenus()
    {
        return $this->hasMany(Submenu::class, 'idMenu', 'idMenu');
    }
}
