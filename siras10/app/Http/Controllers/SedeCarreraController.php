<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\CentroFormador;
use App\Models\MallaCurricular;
use App\Models\MallaSedeCarrera;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\SedeCarrera;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Verificar si el usuario es coordinador de campo clínico
        $coordinadorCentro = null;
        $user = auth()->user();

        if ($user) {
            $coordinador = \App\Models\CoordinadorCampoClinico::where('runUsuario', $user->runUsuario)
                ->with('centroFormador')
                ->first();

            if ($coordinador) {
                $coordinadorCentro = [
                    'idCentroFormador' => $coordinador->idCentroFormador,
                    'nombreCentroFormador' => $coordinador->centroFormador->nombreCentroFormador,
                ];
            }
        }

        return view('gestion-carreras.index', compact('centrosFormadores', 'carrerasBase', 'coordinadorCentro'));
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
            ->orderBy('fechaCreacion', 'desc')
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
        try {
            $request->validate([
                'idSede' => 'required|exists:sede,idSede',
                'idCarrera' => [
                    'required',
                    'exists:carrera,idCarrera',
                    Rule::unique('sede_carrera', 'idCarrera')
                        ->where('idSede', $request->idSede),
                ],
                'codigoCarrera' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('sede_carrera', 'codigoCarrera')
                        ->where('idSede', $request->idSede),
                ],
                'nombreSedeCarrera' => 'nullable|string|max:255',
            ], [
                'idSede.required' => 'Debe seleccionar una sede.',
                'idSede.exists' => 'La sede seleccionada no existe.',
                'idCarrera.required' => 'Debe seleccionar un perfil de carrera.',
                'idCarrera.exists' => 'El perfil seleccionado no existe.',
                'idCarrera.unique' => 'Esta carrera ya está asignada a la sede seleccionada.',
                'codigoCarrera.required' => 'El código de la carrera es obligatorio.',
                'codigoCarrera.max' => 'El código no puede exceder los 50 caracteres.',
                'codigoCarrera.unique' => 'Ya existe una carrera con este código en la sede seleccionada.',
                'nombreSedeCarrera.max' => 'El nombre no puede exceder los 255 caracteres.',
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
                'message' => 'Error al registrar la carrera. Por favor, intente nuevamente.',
            ], 500);
        }
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
            $validated = $request->validate([
                'idSede' => 'required|exists:sede,idSede',
                'idCarrera' => [
                    'required',
                    'exists:carrera,idCarrera',
                    Rule::unique('sede_carrera', 'idCarrera')
                        ->where('idSede', $request->idSede)
                        ->ignore($sedeCarrera->idSedeCarrera, 'idSedeCarrera'),
                ],
                'codigoCarrera' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('sede_carrera', 'codigoCarrera')
                        ->where('idSede', $request->idSede)
                        ->ignore($sedeCarrera->idSedeCarrera, 'idSedeCarrera'),
                ],
                'nombreSedeCarrera' => 'nullable|string|max:255',
            ], [
                'idSede.required' => 'Debe seleccionar una sede.',
                'idSede.exists' => 'La sede seleccionada no existe.',
                'idCarrera.required' => 'Debe seleccionar un perfil de carrera.',
                'idCarrera.exists' => 'El perfil seleccionado no existe.',
                'idCarrera.unique' => 'Esta carrera ya está asignada a la sede seleccionada.',
                'codigoCarrera.required' => 'El código de la carrera es obligatorio.',
                'codigoCarrera.max' => 'El código no puede exceder los 50 caracteres.',
                'codigoCarrera.unique' => 'Ya existe otra carrera con este código en la sede seleccionada.',
                'nombreSedeCarrera.max' => 'El nombre no puede exceder los 255 caracteres.',
            ]);

            // Si no se proporciona nombre específico, usar el de la carrera base
            if (empty($validated['nombreSedeCarrera'])) {
                $carrera = Carrera::find($validated['idCarrera']);
                $validated['nombreSedeCarrera'] = $carrera->nombreCarrera;
            }

            $sedeCarrera->update($validated);

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
                'message' => 'Error al actualizar la carrera. Por favor, intente nuevamente.',
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
                    'fechaSubida' => now(),
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
            'sede.centroFormador',  // Agregar esta relación anidada
            'carrera',
            'mallaSedeCarreras.mallaCurricular',
            'asignaturas.programas',
        ]);

        $asignaturas = $sedeCarrera->asignaturas()
            ->with(['programas' => function ($q) {
                $q->latest('fechaSubida');
            }, 'programa', 'tipoPractica'])
            ->orderBy('nombreAsignatura')
            ->paginate(5, ['*'], 'asignaturas_page');

        $mallas = $sedeCarrera->mallaSedeCarreras()
            ->with('mallaCurricular')
            ->orderByDesc('fechaSubida')
            ->paginate(5, ['*'], 'mallas_page');

        return view('gestion-carreras.archivos', [
            'sedeCarrera' => $sedeCarrera,
            'mallas' => $mallas,
            'asignaturas' => $asignaturas,
        ]);
    }

    public function storePrograma(Request $request, Asignatura $asignatura)
    {
        try {
            // Validación
            $validated = $request->validate([
                'documento' => 'required|file|mimes:pdf|max:2048',
            ]);

            // Iniciar transacción
            \DB::beginTransaction();

            try {
                // No eliminar el programa anterior para mantener historial

                // Procesar el archivo
                $file = $request->file('documento');

                // Generar nombre único para el archivo
                $nombreArchivo = time().'_programa_'.$asignatura->idAsignatura.'_'.$file->getClientOriginalName();
                $documentoPath = $file->storeAs('programas', $nombreArchivo, 'public');

                // Crear el registro del programa
                $programa = Programa::create([
                    'idAsignatura' => $asignatura->idAsignatura,
                    'documento' => $documentoPath,
                    'fechaSubida' => now()->toDateString(),
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Programa guardado correctamente',
                    'data' => [
                        'id' => $programa->idPrograma,
                        'fechaSubida' => $programa->fechaSubida,
                        'size' => round($file->getSize() / (1024 * 1024), 2).'MB',
                    ],
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Error en transacción storePrograma: '.$e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error en storePrograma: '.$e->getMessage());

            $errorMessage = 'Error al guardar el programa.';
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

    public function descargarPrograma(Asignatura $asignatura)
    {
        $programa = $asignatura->programa;

        if (! $programa || ! $programa->doc || ! Storage::disk('public')->exists($programa->doc)) {
            abort(404, 'Programa no encontrado');
        }

        return Storage::disk('public')->download(
            $programa->doc,
            $asignatura->nombreAsignatura.' - Programa.pdf'
        );
    }

    public function verPrograma(Asignatura $asignatura)
    {
        $programa = $asignatura->programa;

        if (! $programa || ! $programa->documento || ! \Storage::disk('public')->exists($programa->documento)) {
            abort(404, 'Programa no encontrado');
        }

        $filePath = storage_path('app/public/'.$programa->documento);
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.($asignatura->nombreAsignatura ?? 'programa').'.pdf"',
        ];

        return response()->file($filePath, $headers);
    }

    public function updateMalla(Request $request, $mallaSedeCarrera)
    {
        try {
            $malla = \App\Models\MallaSedeCarrera::findOrFail($mallaSedeCarrera);

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'anio' => 'required|integer|min:2020|max:2030',
                'documento' => 'nullable|file|mimes:pdf|max:2048',
            ]);

            \DB::beginTransaction();

            try {
                // Actualizar o crear el año en MallaCurricular
                $mallaCurricular = \App\Models\MallaCurricular::firstOrCreate(
                    ['anio' => $validated['anio']],
                    ['fechaCreacion' => now()->toDateString()]
                );

                // Verificar si ya existe otra malla para esta sede-carrera en este año
                $mallaExistente = \App\Models\MallaSedeCarrera::where('idSedeCarrera', $malla->idSedeCarrera)
                    ->where('idMallaCurricular', $mallaCurricular->idMallaCurricular)
                    ->where('idMallaSedeCarrera', '!=', $malla->idMallaSedeCarrera)
                    ->first();

                if ($mallaExistente) {
                    \DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una malla curricular para esta sede en el año '.$validated['anio'],
                    ], 422);
                }

                // Procesar el archivo si se proporciona uno nuevo
                $documentoPath = $malla->documento;
                if ($request->hasFile('documento')) {
                    // Eliminar el archivo anterior
                    if ($malla->documento && \Storage::disk('public')->exists($malla->documento)) {
                        \Storage::disk('public')->delete($malla->documento);
                    }

                    $file = $request->file('documento');
                    $nombreArchivo = time().'_malla_'.$malla->idSedeCarrera.'_'.$file->getClientOriginalName();
                    $documentoPath = $file->storeAs('mallas-curriculares', $nombreArchivo, 'public');
                }

                // Actualizar el registro
                $malla->update([
                    'idMallaCurricular' => $mallaCurricular->idMallaCurricular,
                    'nombre' => $validated['nombre'],
                    'documento' => $documentoPath,
                    'fechaSubida' => now(),
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Malla curricular actualizada correctamente',
                    'data' => $malla->load('mallaCurricular'),
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error en updateMalla: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la malla curricular.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Elimina una malla curricular
     */
    public function destroyMalla($mallaSedeCarrera)
    {
        try {
            $malla = \App\Models\MallaSedeCarrera::findOrFail($mallaSedeCarrera);

            // Eliminar el archivo del almacenamiento
            if ($malla->documento && \Storage::disk('public')->exists($malla->documento)) {
                \Storage::disk('public')->delete($malla->documento);
            }

            $malla->delete();

            return response()->json([
                'success' => true,
                'message' => 'Malla curricular eliminada correctamente',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en destroyMalla: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la malla curricular.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Guarda una nueva asignatura
     */
    public function storeAsignatura(Request $request)
    {
        try {
            $validated = $request->validate([
                'idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
                'nombreAsignatura' => 'required|string|max:255',
                'codAsignatura' => 'required|string|max:50',
                'Semestre' => 'required|integer|min:1|max:12',
                'idTipoPractica' => 'required|exists:tipo_practica,idTipoPractica',
            ]);

            \DB::beginTransaction();

            try {
                $asignatura = Asignatura::create([
                    'nombreAsignatura' => $validated['nombreAsignatura'],
                    'codAsignatura' => $validated['codAsignatura'],
                    'Semestre' => $validated['Semestre'],
                    'idTipoPractica' => $validated['idTipoPractica'],
                    'idSedeCarrera' => $validated['idSedeCarrera'],
                    'fechaCreacion' => now()->toDateString(),
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Asignatura creada exitosamente',
                    'data' => $asignatura->load('tipoPractica'),
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Error en transacción storeAsignatura: '.$e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error en storeAsignatura: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la asignatura.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Obtiene los datos de una asignatura para edición
     */
    public function editAsignatura($asignatura)
    {
        try {
            $asig = Asignatura::with('tipoPractica')->findOrFail($asignatura);

            return response()->json([
                'success' => true,
                'data' => [
                    'idAsignatura' => $asig->idAsignatura,
                    'nombreAsignatura' => $asig->nombreAsignatura,
                    'codAsignatura' => $asig->codAsignatura,
                    'Semestre' => $asig->Semestre,
                    'idTipoPractica' => $asig->idTipoPractica,
                    'idSedeCarrera' => $asig->idSedeCarrera,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en editAsignatura: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la asignatura.',
            ], 500);
        }
    }

    /**
     * Actualiza una asignatura existente
     */
    public function updateAsignatura(Request $request, $asignatura)
    {
        try {
            $asig = Asignatura::findOrFail($asignatura);

            $validated = $request->validate([
                'nombreAsignatura' => 'required|string|max:255',
                'codAsignatura' => 'required|string|max:50',
                'Semestre' => 'required|integer|min:1|max:12',
                'idTipoPractica' => 'required|exists:tipo_practica,idTipoPractica',
            ]);

            \DB::beginTransaction();

            try {
                $asig->update([
                    'nombreAsignatura' => $validated['nombreAsignatura'],
                    'codAsignatura' => $validated['codAsignatura'],
                    'Semestre' => $validated['Semestre'],
                    'idTipoPractica' => $validated['idTipoPractica'],
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Asignatura actualizada correctamente',
                    'data' => $asig->load('tipoPractica'),
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error en updateAsignatura: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la asignatura.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Elimina una asignatura
     */
    public function destroyAsignatura($asignatura)
    {
        try {
            $asig = Asignatura::findOrFail($asignatura);

            // Eliminar todos los programas asociados (y sus archivos)
            foreach ($asig->programas as $programa) {
                if ($programa->documento && \Storage::disk('public')->exists($programa->documento)) {
                    \Storage::disk('public')->delete($programa->documento);
                }
                $programa->delete();
            }

            $asig->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asignatura eliminada correctamente',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en destroyAsignatura: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la asignatura.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Obtiene las asignaturas de una sede-carrera
     */
    public function getAsignaturasPorSedeCarrera($sedeCarrera)
    {
        try {
            $asignaturas = Asignatura::with('tipoPractica')
                ->where('idSedeCarrera', $sedeCarrera)
                ->orderBy('Semestre')
                ->orderBy('nombreAsignatura')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $asignaturas->map(function ($asig) {
                    return [
                        'idAsignatura' => $asig->idAsignatura,
                        'nombreAsignatura' => $asig->nombreAsignatura,
                        'codAsignatura' => $asig->codAsignatura,
                        'Semestre' => $asig->Semestre,
                        'tipoPractica' => $asig->tipoPractica->nombreTipoPractica ?? '',
                        'idTipoPractica' => $asig->idTipoPractica,
                    ];
                }),
            ]);

        } catch (Exception $e) {
            \Log::error('Error en getAsignaturasPorSedeCarrera: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las asignaturas',
            ], 500);
        }
    }

    /**
     * Muestra la lista de programas de una asignatura
     */
    public function showProgramas(Asignatura $asignatura)
    {
        $programas = $asignatura->programas()->orderByDesc('fechaSubida')->paginate(5);
        \Log::debug('[showProgramas] idAsignatura: '.$asignatura->idAsignatura.' | count: '.$programas->count());
        foreach ($programas as $p) {
            \Log::debug('[showProgramas] Programa id: '.$p->idPrograma.' | documento: '.$p->documento);
        }

        return view('asignaturas._programas', compact('programas'));
    }

    /**
     * Descargar un programa específico por id
     */
    public function descargarProgramaEspecifico(\App\Models\Programa $programa)
    {
        if (! $programa->documento || ! \Storage::disk('public')->exists($programa->documento)) {
            abort(404, 'Programa no encontrado');
        }
        $nombre = 'Programa_'.($programa->asignatura->nombreAsignatura ?? 'asignatura').'_'.($programa->fechaSubida ?? '').'.pdf';

        return \Storage::disk('public')->download($programa->documento, $nombre);
    }

    /**
     * Eliminar un programa específico
     */
    public function destroyPrograma(\App\Models\Programa $programa)
    {
        try {
            \DB::beginTransaction();

            // Eliminar el archivo del almacenamiento
            if ($programa->documento && \Storage::disk('public')->exists($programa->documento)) {
                \Storage::disk('public')->delete($programa->documento);
            }

            // Eliminar el registro de la base de datos
            $programa->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Programa eliminado correctamente',
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al eliminar programa: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el programa',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Subir pauta de evaluación para una asignatura
     */
    public function uploadPautaEvaluacion(Request $request, $asignatura)
    {
        try {
            $asig = Asignatura::findOrFail($asignatura);

            $request->validate([
                'documento' => 'required|file|mimes:pdf|max:2048',
            ]);

            \DB::beginTransaction();

            try {
                // Eliminar pauta anterior si existe
                if ($asig->pauta_evaluacion && \Storage::disk('public')->exists($asig->pauta_evaluacion)) {
                    \Storage::disk('public')->delete($asig->pauta_evaluacion);
                }

                // Guardar nueva pauta
                $path = $request->file('documento')->store('pautas-evaluacion', 'public');

                $asig->update(['pauta_evaluacion' => $path]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pauta de evaluación subida correctamente',
                    'data' => [
                        'pauta_evaluacion' => $path,
                        'url' => asset('storage/'.$path),
                    ],
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error en uploadPautaEvaluacion: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al subir la pauta de evaluación',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Descargar pauta de evaluación de una asignatura
     */
    public function descargarPautaEvaluacion($asignatura)
    {
        try {
            $asig = Asignatura::findOrFail($asignatura);

            if (! $asig->pauta_evaluacion || ! \Storage::disk('public')->exists($asig->pauta_evaluacion)) {
                abort(404, 'Pauta de evaluación no encontrada');
            }

            $nombre = 'Pauta_Evaluacion_'.str_replace(' ', '_', $asig->nombreAsignatura).'.pdf';

            return \Storage::disk('public')->download($asig->pauta_evaluacion, $nombre);

        } catch (\Exception $e) {
            \Log::error('Error al descargar pauta: '.$e->getMessage());
            abort(404, 'Pauta de evaluación no encontrada');
        }
    }

    /**
     * Eliminar pauta de evaluación de una asignatura
     */
    public function destroyPautaEvaluacion($asignatura)
    {
        try {
            $asig = Asignatura::findOrFail($asignatura);

            \DB::beginTransaction();

            try {
                // Eliminar el archivo del almacenamiento
                if ($asig->pauta_evaluacion && \Storage::disk('public')->exists($asig->pauta_evaluacion)) {
                    \Storage::disk('public')->delete($asig->pauta_evaluacion);
                }

                // Actualizar el registro
                $asig->update(['pauta_evaluacion' => null]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pauta de evaluación eliminada correctamente',
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error al eliminar pauta: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la pauta de evaluación',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
