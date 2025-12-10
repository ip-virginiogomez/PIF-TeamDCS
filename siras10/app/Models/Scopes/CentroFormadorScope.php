<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CentroFormadorScope implements Scope
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

        if ($user->esCoordinador()) {
            // Filtrar por fechas de vigencia
            $centroIds = $user->centrosFormadores()
                ->wherePivot('fechaInicio', '<=', now()->toDateString())
                ->wherePivot('fechaFin', '>=', now()->toDateString())
                ->pluck('centro_formador.idCentroFormador')
                ->toArray();

            if (empty($centroIds)) {
                $builder->whereRaw('1 = 0');

                return;
            }
            $tableName = $model->getTable();

            if ($tableName === 'alumno') {
                $builder->whereHas('alumnoCarreras.sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'docente') {
                $builder->whereHas('docenteCarreras.sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'sede') {
                $builder->whereIn('idCentroFormador', $centroIds);
            } elseif ($tableName === 'convenio') {
                $builder->whereIn('idCentroFormador', $centroIds);
            } elseif ($tableName === 'sede_carrera') {
                $builder->whereHas('sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'docente_carrera') {
                $builder->whereHas('sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'alumno_carrera') {
                $builder->whereHas('sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'cupo_distribucion') {
                $builder->whereHas('cupoDemanda.sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } elseif ($tableName === 'cupo_demanda') {
                $builder->whereHas('sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            }
        }
    }
}
