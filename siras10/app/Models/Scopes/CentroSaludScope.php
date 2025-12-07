<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CentroSaludScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->esAdmin()) {
            return;
        }

        if ($user->esTecnicoRAD()) {
            // Filtrar por fechas de vigencia
            $centroIds = $user->centroSalud()
                ->wherePivot('fechaInicio', '<=', now()->toDateString())
                ->wherePivot('fechaFin', '>=', now()->toDateString())
                ->pluck('centro_salud.idCentroSalud')
                ->toArray();

            if (empty($centroIds)) {
                $builder->whereRaw('1 = 0');

                return;
            }
            $tableName = $model->getTable();

            if ($tableName === 'unidad_clinica') {
                $builder->whereIn('idCentroSalud', $centroIds);
            } elseif ($tableName === 'cupo_oferta') {
                $builder->whereHas('unidadClinica', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroSalud', $centroIds);
                });
            }
        }
    }
}
