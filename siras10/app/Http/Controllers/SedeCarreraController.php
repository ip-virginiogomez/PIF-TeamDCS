<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CentroFormador;
use App\Models\Sede;
use App\Models\SedeCarrera;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SedeCarreraController extends Controller
{
    /**
     * Define las reglas de validación para crear y actualizar una SedeCarrera.
     * Centraliza las reglas para evitar duplicación de código.
     */
    private function getValidationRules(): array
    {
        return [
            'idSede' => 'required|exists:sede,idSede',
            'idCarrera' => 'required|exists:carrera,idCarrera',
            'nombreSedeCarrera' => 'required|string|max:255',
            'codigoCarrera' => 'required|string|max:50',
        ];
    }

    /**
     * Muestra la página principal de gestión con los datos necesarios para los selectores.
     */
    public function index(): View
    {
        $centrosFormadores = CentroFormador::with('sedes')->orderBy('nombreCentroFormador')->get();
        $carrerasBase = Carrera::orderBy('nombreCarrera')->get();

        return view('sede-carrera.index', compact('centrosFormadores', 'carrerasBase'));
    }

    /**
     * Devuelve una lista de carreras específicas de una sede en formato JSON.
     *
     * @param  Sede  $sede  El modelo Sede inyectado por Route Model Binding.
     */
    public function getCarrerasAsJson(Sede $sede): JsonResponse
    {
        try {
            $carreras = $sede->sedeCarreras()->with('carrera')->get();

            return response()->json($carreras);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las carreras.'], 500);
        }
    }

    /**
     * Devuelve solo la tabla de carreras en formato HTML.
     *
     * @return View|Response
     */
    public function getTablaAsHtml(Sede $sede)
    {
        try {
            $carrerasEspecificas = $sede->sedeCarreras()->with('carrera')->get();

            return view('sede-carrera._tabla', compact('sede', 'carrerasEspecificas'));
        } catch (Exception $e) {
            return response('Error al renderizar la tabla: '.$e->getMessage(), 500);
        }
    }

    /**
     * Este método ya no se usará, pero lo dejamos por si se necesita en el futuro.
     */
    public function getGestionAsHtml(Sede $sede)
    {
        // Ya no se utiliza esta lógica. La tabla se carga por separado.
        // Devolvemos una respuesta vacía para evitar errores si se llama accidentalmente.
        return response('');
    }

    /**
     * Almacena una nueva carrera específica para una sede.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate($this->getValidationRules());
        SedeCarrera::create($validatedData);

        return response()->json(['message' => 'Carrera asignada con éxito.']);
    }

    /**
     * Devuelve los datos de una carrera específica para el formulario de edición.
     */
    public function edit(SedeCarrera $sedeCarrera): JsonResponse
    {
        return response()->json($sedeCarrera);
    }

    /**
     * Actualiza una carrera específica existente.
     */
    public function update(Request $request, SedeCarrera $sedeCarrera): JsonResponse
    {
        $validatedData = $request->validate($this->getValidationRules());
        $sedeCarrera->update($validatedData);

        return response()->json(['message' => 'Carrera actualizada con éxito.']);
    }

    /**
     * Elimina una carrera específica de una sede.
     */
    public function destroy(SedeCarrera $sedeCarrera): JsonResponse
    {
        $sedeCarrera->delete();

        return response()->json(['message' => 'Carrera eliminada con éxito.']);
    }
}
