<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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

    protected static function booted()
    {
        static::deleted(function ($menu) {
            $menu->submenus()->each(function ($submenu) {
                $submenu->delete();
            });
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
