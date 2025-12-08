<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Submenu extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'submenu';

    public $timestamps = false;

    protected $primaryKey = 'idSubmenu';

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

    protected static function booted()
    {
        static::deleted(function ($submenu) {
            $submenu->permisos()->each(function ($permiso) {
                $permiso->delete();
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
