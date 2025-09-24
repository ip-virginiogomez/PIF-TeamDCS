<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\TipoCentroFormador; // Importamos el modelo relacionado
use Illuminate\Http\Request;

class CentroFormadorController extends Controller
{
    /**
     * Muestra una lista de todos los centros formadores.
     */
    public function index()
    {
        // Usamos with() para cargar la relación y evitar consultas N+1 (más eficiente)
        $centros = CentroFormador::with('tipoCentroFormador')->paginate(10);

        return view('admin.centros-formadores.index', compact('centros'));
    }

    /**
     * Muestra el formulario para crear un nuevo centro formador.
     */
    public function create()
    {
        // Obtenemos todos los tipos para poder listarlos en un <select> en el formulario
        $tipos = TipoCentroFormador::all();

        return view('admin.centros-formadores.create', compact('tipos'));
    }

    /**
     * Guarda un nuevo centro formador en la base de datos.
     */
    public function store(Request $request)
    {
        // ----- INICIO DE LA CORRECCIÓN -----
        $request->validate([
            'nombreCentroFormador' => 'required|string|max:45',
            // Usamos la clase del modelo para que respete el nombre de la tabla
            'idTipoCentroFormador' => ['required', 'exists:App\Models\TipoCentroFormador,idTipoCentroFormador'],
        ]);
        // ----- FIN DE LA CORRECCIÓN -----

        CentroFormador::create($request->all());

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador creado exitosamente.');
    }

    /**
     * Muestra los detalles de un centro formador específico.
     */
    public function show(CentroFormador $centros_formadore)
    {
        // Laravel inyecta el modelo directamente. La variable se llama así por el nombre del resource.
        return view('admin.centros-formadores.show', compact('centros_formadore'));
    }

    /**
     * Muestra el formulario para editar un centro formador.
     */
    public function edit(CentroFormador $centros_formadore)
    {
        // También necesitamos la lista de tipos aquí, por si el usuario quiere cambiarlo
        $tipos = TipoCentroFormador::all();

        return view('admin.centros-formadores.edit', compact('centros_formadore', 'tipos'));
    }

    /**
     * Actualiza un centro formador en la base de datos.
     */
    public function update(Request $request, CentroFormador $centros_formadore)
    {
        // ----- INICIO DE LA CORRECCIÓN -----
        $request->validate([
            'nombreCentroFormador' => 'required|string|max:45',
            // Usamos la clase del modelo también aquí
            'idTipoCentroFormador' => ['required', 'exists:App\Models\TipoCentroFormador,idTipoCentroFormador'],
        ]);
        // ----- FIN DE LA CORRECCIÓN -----

        $centros_formadore->update($request->all());

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador actualizado exitosamente.');
    }

    /**
     * Elimina un centro formador de la base de datos.
     */
    public function destroy(CentroFormador $centros_formadore)
    {
        $centros_formadore->delete();

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador eliminado exitosamente.');
    }
}
