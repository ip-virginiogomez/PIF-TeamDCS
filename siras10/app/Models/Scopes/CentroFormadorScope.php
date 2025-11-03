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
        if(!Auth::check()){
            return;
        }

        $user = Auth::user();

        if($user->esAdmin()){
            return;
        }

        if($user->esCoordinador()){
            $centroIds = $user->centrosFormadores->pluck('idCentroFormador')->toArray();
            if (empty($centroIds)) {
                $builder->whereRaw('1 = 0');
                return;
            }
            $tableName = $model->getTable();

            if ($tableName === 'Alumno') {
                $builder->whereHas('alumnoCarreras.sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            } 
            
            elseif ($tableName === 'Docente') {
                $builder->whereHas('docenteCarreras.sedeCarrera.sede', function ($query) use ($centroIds) {
                    $query->whereIn('idCentroFormador', $centroIds);
                });
            }
            elseif ($tableName === 'Sede') {
                $builder->whereIn('idCentroFormador', $centroIds);
            }
            
            elseif ($tableName === 'Convenio') {
                $builder->whereIn('idCentroFormador', $centroIds);
            }
        }
    }
}
