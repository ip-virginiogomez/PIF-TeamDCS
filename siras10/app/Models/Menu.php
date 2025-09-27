<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $primaryKey = 'idMenu';

    public $timestamps = false;

    protected $fillable = [
        'nombreMenu',
    ];

    // Relación uno a muchos con Submenu
    public function submenus()
    {
        return $this->hasMany(Submenu::class, 'idMenu', 'idMenu');
    }
}
