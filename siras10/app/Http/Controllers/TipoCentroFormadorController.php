<?php

namespace App\Http\Controllers;

use App\Models\TipoCentroFormador;
use Illuminate\Http\Request;

class TipoCentroFormadorController extends Controller
{
    /**
     * Muestra una lista de todos los tipos de centro formador.
     */
    public function index()
    {
        // Obtenemos todos los registros de la tabla y los paginamos (10 por página)
        $tipos = TipoCentroFormador::paginate(10);
        
        // Retornamos la vista 'index' y le pasamos la variable 'tipos'
        return view('admin.tipos-centro.index', compact('tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo tipo de centro.
     */
    public function create()
    {
        return view('admin.tipos-centro.create');
    }

    /**
     * Guarda un nuevo tipo de centro en la base de datos.
     */
    public function store(Request $request)
    {
        // Validamos los datos que vienen del formulario
        $request->validate([
            'nombreTipo' => 'required|string|max:45',
            'acronimo' => 'nullable|string|max:10',
        ]);

        // Creamos un nuevo registro con los datos validados
        TipoCentroFormador::create($request->all());

        // Redirigimos al usuario a la lista de tipos con un mensaje de éxito
        return redirect()->route('tipos-centro-formador.index')
                         ->with('success', 'Tipo de Centro Formador creado exitosamente.');
    }

    /**
     * Muestra los detalles de un tipo de centro específico. (Opcional)
     */
    public function show(TipoCentroFormador $tipos_centro_formador)
    {
        // Nota: Laravel usa el nombre de la variable en singular del 'resource'
        // Por eso es $tipos_centro_formador
        return view('admin.tipos-centro.show', compact('tipos_centro_formador'));
    }

    /**
     * Muestra el formulario para editar un tipo de centro existente.
     */
    public function edit(TipoCentroFormador $tipos_centro_formador)
    {
        return view('admin.tipos-centro.edit', compact('tipos_centro_formador'));
    }

    /**
     * Actualiza un tipo de centro en la base de datos.
     */
    public function update(Request $request, TipoCentroFormador $tipos_centro_formador)
    {
        // Validamos los datos
        $request->validate([
            'nombreTipo' => 'required|string|max:45',
            'acronimo' => 'nullable|string|max:10',
        ]);

        // Actualizamos el registro existente
        $tipos_centro_formador->update($request->all());

        // Redirigimos a la lista con un mensaje de éxito
        return redirect()->route('tipos-centro-formador.index')
                         ->with('success', 'Tipo de Centro Formador actualizado exitosamente.');
    }

    /**
     * Elimina un tipo de centro de la base de datos.
     */
    public function destroy(TipoCentroFormador $tipos_centro_formador)
    {
        // Eliminamos el registro
        $tipos_centro_formador->delete();

        // Redirigimos a la lista con un mensaje de éxito
        return redirect()->route('tipos-centro-formador.index')
                         ->with('success', 'Tipo de Centro Formador eliminado exitosamente.');
    }
}