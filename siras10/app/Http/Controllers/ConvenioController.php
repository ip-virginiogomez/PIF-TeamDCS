<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\Convenio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ConvenioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $columnasDisponibles = [
                'idConvenio', 'fechaSubida', 'anioValidez',
                'centro_formador.nombreCentroFormador',
            ];

            $sortBy = request()->get('sort_by', 'idConvenio');
            $sortDirection = request()->get('sort_direction', 'desc');

            if (! in_array($sortBy, $columnasDisponibles)) {
                $sortBy = 'idConvenio';
            }

            $query = Convenio::query();

            $query->with(['centroFormador']);

            if (strpos($sortBy, '.') !== false) {
                [$tableRelacion, $columna] = explode('.', $sortBy);
                if ($tableRelacion === 'centro_formador') {
                    $query->join('centro_formador', 'convenio.idCentroFormador', '=', 'centro_formador.idCentroFormador')
                        ->orderBy('centro_formador.'.$columna, $sortDirection)
                        ->select('convenio.*');
                }
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }

            $convenios = $query->paginate(10);

            if ($request->ajax()) {
                return view('convenios._tabla', [
                    'convenios' => $convenios,
                    'sortBy' => $sortBy,
                    'sortDirection' => $sortDirection,
                ])->render();
            }

            $centrosFormadores = CentroFormador::all();

            return view('convenios.index', [
                'convenios' => $convenios,
                'centrosFormadores' => $centrosFormadores,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en ConvenioController@index: '.$e->getMessage());

            return view('convenios.index', [
                'convenios' => collect(),
                'centrosFormadores' => CentroFormador::all(),
                'sortBy' => 'idConvenio',
                'sortDirection' => 'desc',
            ])->with('error', 'Error al cargar datos');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'idCentroFormador' => 'required|exists:centro_formador,idCentroFormador',
            'anioValidez' => 'required|integer|max:'.(date('Y') + 10),
        ], [
            'documento.required' => 'El documento del convenio es obligatorio.',
            'documento.file' => 'Debe seleccionar un archivo válido.',
            'documento.mimes' => 'El documento debe ser un archivo PDF, DOC o DOCX.',
            'documento.max' => 'El documento no puede superar los 10MB.',
            'idCentroFormador.required' => 'Debe seleccionar un centro formador.',
            'idCentroFormador.exists' => 'El centro formador seleccionado no es válido.',
            'anioValidez.required' => 'El año de validez es obligatorio.',
            'anioValidez.integer' => 'El año de validez debe ser un número entero.',
            'anioValidez.max' => 'El año de validez no puede superar 10 años desde hoy.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $documentoPath = null;

            // Manejar la subida del documento
            if ($request->hasFile('documento')) {
                $archivo = $request->file('documento');
                $nombreArchivo = time().'_convenio_'.$archivo->getClientOriginalName();
                $documentoPath = $archivo->storeAs('convenios', $nombreArchivo, 'public');
            }

            $convenio = Convenio::create([
                'documento' => $documentoPath,
                'idCentroFormador' => $request->idCentroFormador,
                'fechaSubida' => Carbon::now()->format('Y-m-d'),
                'anioValidez' => $request->anioValidez,
            ]);

            $convenio->load('centroFormador');

            return response()->json([
                'success' => true,
                'message' => 'Convenio creado exitosamente.',
                'convenio' => $convenio,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el convenio: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $convenio = Convenio::with(['centroFormador'])->findOrFail($id);

        return response()->json($convenio);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $convenio = Convenio::findOrFail($id);

        return response()->json($convenio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $convenio = Convenio::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'documento' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB - opcional en edición
            'idCentroFormador' => 'required|exists:centro_formador,idCentroFormador',
            'anioValidez' => 'required|integer|max:'.(date('Y') + 10),
        ], [
            'documento.file' => 'Debe seleccionar un archivo válido.',
            'documento.mimes' => 'El documento debe ser un archivo PDF, DOC o DOCX.',
            'documento.max' => 'El documento no puede superar los 10MB.',
            'idCentroFormador.required' => 'Debe seleccionar un centro formador.',
            'idCentroFormador.exists' => 'El centro formador seleccionado no es válido.',
            'anioValidez.required' => 'El año de validez es obligatorio.',
            'anioValidez.integer' => 'El año de validez debe ser un número entero.',
            'anioValidez.max' => 'El año de validez no puede superar 10 años desde hoy.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $datosActualizar = [
                'idCentroFormador' => $request->idCentroFormador,
                'anioValidez' => $request->anioValidez,
            ];

            // Manejar la subida del nuevo documento si se proporciona
            if ($request->hasFile('documento')) {
                // Eliminar el documento anterior si existe
                if ($convenio->documento && Storage::disk('public')->exists($convenio->documento)) {
                    Storage::disk('public')->delete($convenio->documento);
                }

                // Subir el nuevo documento
                $archivo = $request->file('documento');
                $nombreArchivo = time().'_convenio_'.$archivo->getClientOriginalName();
                $documentoPath = $archivo->storeAs('convenios', $nombreArchivo, 'public');
                $datosActualizar['documento'] = $documentoPath;
            }

            $convenio->update($datosActualizar);
            $convenio->load('centroFormador');

            return response()->json([
                'success' => true,
                'message' => 'Convenio actualizado exitosamente.',
                'convenio' => $convenio,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el convenio: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $convenio = Convenio::findOrFail($id);

            // Eliminar el documento del storage si existe
            if ($convenio->documento && Storage::disk('public')->exists($convenio->documento)) {
                Storage::disk('public')->delete($convenio->documento);
            }

            $convenio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Convenio eliminado exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el convenio: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Descargar el documento del convenio
     */
    public function descargarDocumento(string $id)
    {
        try {
            $convenio = Convenio::findOrFail($id);

            if (! $convenio->documento || ! Storage::disk('public')->exists($convenio->documento)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El documento no existe.',
                ], 404);
            }

            return Storage::disk('public')->download($convenio->documento);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al descargar el documento: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver el documento del convenio en el navegador
     */
    public function verDocumento(string $id)
    {
        try {
            $convenio = Convenio::findOrFail($id);

            if (! $convenio->documento || ! Storage::disk('public')->exists($convenio->documento)) {
                abort(404, 'El documento no existe.');
            }

            $path = storage_path('app/public/'.$convenio->documento);

            return response()->file($path);
        } catch (\Exception $e) {
            abort(404, 'Error al mostrar el documento.');
        }
    }
}
