<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission; // Usamos el modelo de Permission

class PermissionController extends Controller
{
    /**
     * Muestra una lista de todos los permisos.
     */
    public function index()
    {
        $permisos = Permission::orderBy('name')->paginate(20); // Ordenamos alfabéticamente

        return view('permisos.index', compact('permisos'));
    }
}
