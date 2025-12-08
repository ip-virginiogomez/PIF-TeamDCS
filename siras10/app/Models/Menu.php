<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'menu';

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
