<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CentroFormador;
use App\Models\MallaCurricular;
use App\Models\MallaSedeCarrera;
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
        $this->middleware('permission:sede-carrera.create')->only('store', 'storeMalla');
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

    /**
     * Guarda una malla curricular para una carrera específica de sede
     */
    public function storeMalla(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
                'nombre' => 'required|string|max:255',
                'anio' => 'required|integer|min:2020|max:2030',
                'documento' => 'required|file|mimes:pdf|max:2048',
            ], [
                'anio.required' => 'El año es obligatorio.',
                'anio.integer' => 'El año debe ser un número entero.',
                'anio.min' => 'El año no puede ser menor a 2020.',
                'anio.max' => 'El año no puede ser mayor a 2030.',
            ]);

            // Iniciar transacción
            \DB::beginTransaction();

            try {
                // AUTO-CREAR el año en MallaCurricular si no existe
                $mallaCurricular = \App\Models\MallaCurricular::firstOrCreate(
                    ['anio' => $validated['anio']],
                    ['fechaCreacion' => now()->toDateString()]
                );

                // Verificar si ya existe una malla para esta sede-carrera en este año
                $mallaExistente = \App\Models\MallaSedeCarrera::where('idSedeCarrera', $validated['idSedeCarrera'])
                    ->where('idMallaCurricular', $mallaCurricular->idMallaCurricular)
                    ->first();

                if ($mallaExistente) {
                    \DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una malla curricular para esta sede en el año '.$validated['anio'],
                    ], 422);
                }

                // Procesar el archivo - ALMACENAR EN DISCO
                $file = $request->file('documento');

                // Generar nombre único para el archivo
                $nombreArchivo = time().'_malla_'.$validated['idSedeCarrera'].'_'.$file->getClientOriginalName();
                $documentoPath = $file->storeAs('mallas-curriculares', $nombreArchivo, 'public');

                // Crear registro en la tabla intermedia
                $mallaSedeCarrera = \App\Models\MallaSedeCarrera::create([
                    'idMallaCurricular' => $mallaCurricular->idMallaCurricular,
                    'idSedeCarrera' => $validated['idSedeCarrera'],
                    'nombre' => $validated['nombre'],
                    'documento' => $documentoPath,
                    'fechaSubida' => now()->toDateString(),
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Malla curricular guardada exitosamente para el año '.$validated['anio'],
                    'data' => [
                        'id' => $mallaSedeCarrera->idMallaSedeCarrera,
                        'nombre' => $mallaSedeCarrera->nombre,
                        'anio' => $mallaCurricular->anio,
                        'size' => round($file->getSize() / (1024 * 1024), 2).'MB',
                    ],
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Error en transacción storeMalla: '.$e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error en storeMalla: '.$e->getMessage());

            $errorMessage = 'Error al guardar la malla curricular.';
            if (config('app.debug')) {
                $errorMessage .= ' '.$e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function getAniosDisponibles()
    {
        $anios = MallaCurricular::getAniosDisponibles();

        return response()->json([
            'success' => true,
            'data' => $anios,
        ]);
    }

    public function getMallasPorSede(Request $request, $sedeId)
    {
        try {
            $anio = $request->input('anio');

            // Obtener todas las carreras de la sede
            $sedeCarreras = SedeCarrera::where('idSede', $sedeId)->pluck('idSedeCarrera');

            // Query base para mallas
            $query = MallaSedeCarrera::with(['sedeCarrera.carrera', 'mallaCurricular'])
                ->whereIn('idSedeCarrera', $sedeCarreras);

            // Filtrar por año si se proporciona
            if ($anio) {
                $query->whereHas('mallaCurricular', function ($q) use ($anio) {
                    $q->where('anio', $anio);
                });
            }

            $mallas = $query->orderBy('fechaSubida', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $mallas->map(function ($malla) {
                    return [
                        'id' => $malla->idMallaSedeCarrera,
                        'nombre' => $malla->nombre,
                        'anio' => $malla->mallaCurricular->anio,
                        'fechaSubida' => $malla->fechaSubida,
                        'carrera' => $malla->sedeCarrera->carrera->nombreCarrera,
                        'codigoCarrera' => $malla->sedeCarrera->codigoCarrera,
                        'documento' => $malla->documento,
                    ];
                }),
                'sedeId' => $sedeId,
            ]);

        } catch (Exception $e) {
            \Log::error('Error en getMallasPorSede: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las mallas curriculares',
            ], 500);
        }
    }
    public function archivos(SedeCarrera $sedeCarrera)
    {
        $sedeCarrera->load([
            'sede',
            'carrera',
            'mallaSedeCarreras.mallaCurricular',
            'asignaturas.programa',
        ]);

        $asignaturas = $sedeCarrera->asignaturas()
            ->with(['programa' => function ($q) {
                $q->latest('fechaSubida');
            }])
            ->orderBy('nombreAsignatura')
            ->get();

        $mallas = $sedeCarrera->mallaSedeCarreras()
            ->with('mallaCurricular')
            ->orderByDesc('fechaSubida')
            ->get();

        return view('gestion-carreras.archivos', [
            'sedeCarrera' => $sedeCarrera,
            'mallas' => $mallas,
            'asignaturas' => $asignaturas,
        ]);
    }
    public function storePrograma(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($asignatura->programa) {
            Storage::disk('public')->delete($asignatura->programa->documento);
            $asignatura->programa()->delete();
        }

        $file = $request->file('documento');
        $path = $file->store('programas', 'public');

        $programa = $asignatura->programa()->create([
            'documento' => $path,
            'fechaSubida' => now(),
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Programa guardado correctamente',
            'data' => $programa,
        ]);
    }

    public function descargarPrograma(Asignatura $asignatura)
    {
        $programa = $asignatura->programa;

        if (!$programa || !Storage::disk('public')->exists($programa->documento)) {
            abort(404, 'Programa no encontrado');
        }

        return Storage::disk('public')->download(
            $programa->documento,
            $asignatura->nombreAsignatura . ' - Programa.pdf'
        );
    }

    public function verPrograma(Asignatura $asignatura)
    {
        $programa = $asignatura->programa;

        if (!$programa || !Storage::disk('public')->exists($programa->documento)) {
            abort(404, 'Programa no encontrado');
        }

        return response()->file(storage_path('app/public/' . $programa->documento));
    }
}
