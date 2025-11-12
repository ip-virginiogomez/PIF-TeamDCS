<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CentroFormadorScope;

class Convenio extends Model
{
    use HasFactory;

    protected $table = 'convenio';

    protected $primaryKey = 'idConvenio';

    public $timestamps = false;

    protected $fillable = [
        'documento',
        'idCentroFormador',
        'fechaSubida',
        'anioValidez',
    ];

    // Relación inversa con CentroFormador
    public function centroFormador()
    {
        return $this->belongsTo(CentroFormador::class, 'idCentroFormador', 'idCentroFormador');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CentroFormadorScope);
    }
}
