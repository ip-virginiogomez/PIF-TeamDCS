<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CentroFormador;
use App\Models\Sede;
use App\Models\SedeCarrera;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SedeCarreraController extends Controller
{
    /**
     * Define las reglas de validación para crear y actualizar una SedeCarrera.
     */
    private function getValidationRules(): array
    {
        return [
            'idSede' => 'required|exists:sede,idSede',
            'idCarrera' => 'required|exists:carrera,idCarrera',
            'nombreSedeCarrera' => 'nullable|string|max:255',
            'codigoCarrera' => 'required|string|max:50|unique:sede_carrera,codigoCarrera',
        ];
    }

    /**
     * Muestra la página principal de gestión con los datos necesarios para los selectores.
     */
    public function index(): View
    {
        $centrosFormadores = CentroFormador::with('sedes')->orderBy('nombreCentroFormador')->get();
        $carrerasBase = Carrera::select('idCarrera', 'nombreCarrera')
            ->orderBy('nombreCarrera')
            ->get();

        return view('sede-carrera.index', compact('centrosFormadores', 'carrerasBase'));
    }

    /**
     * Devuelve una lista de carreras específicas de una sede en formato JSON.
     */
    public function getCarrerasAsJson(Sede $sede): JsonResponse
    {
        try {
            $carreras = $sede->sedeCarreras()
                ->with('carrera')
                ->orderBy('nombreSedeCarrera')
                ->get();

            return response()->json($carreras);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al cargar carreras'], 500);
        }
    }

    /**
     * Devuelve solo la tabla de carreras en formato HTML.
     */
    public function getTablaAsHtml(Sede $sede)
    {
        try {
            // Obtener las carreras específicas de la sede con la relación carrera
            $carrerasEspecificas = $sede->sedeCarreras()
                ->with('carrera')
                ->orderBy('nombreSedeCarrera')
                ->get();

            // Verificar que la vista existe
            if (! view()->exists('sede-carrera._tabla')) {
                throw new Exception('Vista sede-carrera._tabla no encontrada');
            }

            return view('sede-carrera._tabla', compact('sede', 'carrerasEspecificas'));

        } catch (Exception $e) {
            // Log del error para debugging
            \Log::error('Error en getTablaAsHtml: '.$e->getMessage(), [
                'sede_id' => $sede->idSede,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve el contenedor de gestión completo
     */
    public function getGestionAsHtml(Sede $sede)
    {
        try {
            $carrerasEspecificas = $sede->sedeCarreras()
                ->with('carrera')
                ->orderBy('nombreSedeCarrera')
                ->get();

            return view('sede-carrera._gestion', compact('sede', 'carrerasEspecificas'));
        } catch (Exception $e) {
            \Log::error('Error en getGestionAsHtml: '.$e->getMessage());

            return response()->view('errors.500', [], 500);
        }
    }

    /**
     * Almacena una nueva SedeCarrera
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate($this->getValidationRules());

            // Si no se proporciona nombre específico, usar el de la carrera base
            if (empty($validatedData['nombreSedeCarrera'])) {
                $carrera = Carrera::find($validatedData['idCarrera']);
                $validatedData['nombreSedeCarrera'] = $carrera->nombreCarrera;
            }

            $sedeCarrera = SedeCarrera::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Carrera asignada a la sede correctamente',
                'data' => $sedeCarrera->load('carrera'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Log::error('Error en store SedeCarrera: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
            ], 500);
        }
    }

    /**
     * Devuelve los datos de una SedeCarrera para edición
     */
    public function edit(SedeCarrera $sedeCarrera): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $sedeCarrera->load('carrera', 'sede'),
            ]);
        } catch (Exception $e) {
            \Log::error('Error en edit SedeCarrera: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos',
            ], 500);
        }
    }

    /**
     * Actualiza una SedeCarrera existente
     */
    public function update(Request $request, SedeCarrera $sedeCarrera): JsonResponse
    {
        try {
            $rules = $this->getValidationRules();
            // Excluir el registro actual de la validación unique
            $rules['codigoCarrera'] = 'required|string|max:50|unique:sede_carrera,codigoCarrera,'.$sedeCarrera->idSedeCarrera.',idSedeCarrera';

            $validatedData = $request->validate($rules);

            // Si no se proporciona nombre específico, usar el de la carrera base
            if (empty($validatedData['nombreSedeCarrera'])) {
                $carrera = Carrera::find($validatedData['idCarrera']);
                $validatedData['nombreSedeCarrera'] = $carrera->nombreCarrera;
            }

            $sedeCarrera->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Carrera actualizada correctamente',
                'data' => $sedeCarrera->load('carrera'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Log::error('Error en update SedeCarrera: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
            ], 500);
        }
    }

    /**
     * Elimina una SedeCarrera
     */
    public function destroy(SedeCarrera $sedeCarrera): JsonResponse
    {
        try {
            $sedeCarrera->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carrera eliminada de la sede correctamente',
            ]);

        } catch (Exception $e) {
            \Log::error('Error en destroy SedeCarrera: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la carrera',
            ], 500);
        }
    }
}
