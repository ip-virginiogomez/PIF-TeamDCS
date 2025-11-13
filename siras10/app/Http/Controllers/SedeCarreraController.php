<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CentroFormador;
use App\Models\Sede;
use App\Models\SedeCarrera;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SedeCarreraController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sede-carrera.read')->only('index', 'getCarrerasAsJson', 'getTablaAsHtml', 'getGestionAsHtml');
        $this->middleware('permission:sede-carrera.create')->only('store');
        $this->middleware('permission:sede-carrera.update')->only('edit', 'update');
        $this->middleware('permission:sede-carrera.delete')->only('destroy');
    }

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

        return view('gestion-carreras.index', compact('centrosFormadores', 'carrerasBase'));
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
    // SedeCarreraController.php
    public function getTablaAsHtml($sedeId)
    {
        $carrerasEspecificas = SedeCarrera::with('carrera')
            ->where('idSede', $sedeId)
            ->orderBy('created_at', 'desc')
            ->get();

        // CORRECTO: 'gestion-carreras._tabla'
        return view('gestion-carreras._tabla', compact('carrerasEspecificas'))->render();
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
    public function store(Request $request)
    {
        $request->validate([
            'idSede' => 'required|exists:sede,idSede',
            'idCarrera' => 'required|exists:carrera,idCarrera',
            'codigoCarrera' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sede_carrera', 'codigoCarrera')
                    ->where('idSede', $request->idSede),
            ],
            'nombreSedeCarrera' => 'nullable|string|max:255',
        ]);

        $carrera = SedeCarrera::create([
            'idSede' => $request->idSede,
            'idCarrera' => $request->idCarrera,
            'nombreSedeCarrera' => $request->nombreSedeCarrera,
            'codigoCarrera' => $request->codigoCarrera,
            'fechaCreacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Carrera asignada correctamente',
            'data' => $carrera,
        ]);
    }

    /**
     * Devuelve los datos de una SedeCarrera para edición
     */
    public function edit($sedeCarrera)
    {
        $sc = SedeCarrera::with('carrera')->findOrFail($sedeCarrera);

        return response()->json([
            'success' => true,
            'data' => [
                'idSede' => $sc->idSede,
                'idCarrera' => $sc->idCarrera,
                'nombreSedeCarrera' => $sc->nombreSedeCarrera,
                'codigoCarrera' => $sc->codigoCarrera,
            ],
        ]);
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
