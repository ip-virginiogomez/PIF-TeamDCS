<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocentesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:docentes.read')->only('index');
        $this->middleware('can:docentes.create')->only(['create', 'store']);
        $this->middleware('can:docentes.update')->only(['edit', 'update']);
        $this->middleware('can:docentes.delete')->only('destroy');
    }

    public function index()
    {
        $docentes = Docente::paginate(10);
        return view('admin.docentes.index', compact('docentes'));
    }

    public function store(Request $request)
    {
        \Log::info('=== STORE DOCENTE ===');
        \Log::info('Request data:', $request->all());

        try {
            $validated = $request->validate([
                'runDocente' => 'required|string|max:12|unique:docente,runDocente',
                'nombresDocente' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:docente,correo',
                'fechaNacto' => 'required|date',
                'profesion' => 'required|string|max:100',
                'foto' => 'nullable|image|max:2048',
                'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            \Log::info('Validación exitosa');

            // Preparar datos para guardar
            $data = collect($validated)->except([
                'foto', 'curriculum', 'certSuperInt', 
                'certRCP', 'certIAAS', 'acuerdo'
            ])->toArray();

            // Limpiar el RUN (quitar puntos y guiones)
            /*
            if (isset($data['runDocente'])) {
                $data['runDocente'] = str_replace(['.', '-', ' '], '', $data['runDocente']);
            }
            */
            // Procesar archivos
            $fileFields = [
                'foto' => 'docentes/fotos',
                'curriculum' => 'docentes/curriculums',
                'certSuperInt' => 'docentes/certificados',
                'certRCP' => 'docentes/certificados',
                'certIAAS' => 'docentes/certificados',
                'acuerdo' => 'docentes/acuerdos'
            ];

            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store($path, 'public');
                }
            }

            // Agregar fecha de creación
            $data['fechaCreacion'] = now();

            \Log::info('Datos finales a guardar:', $data);

            // ✅ GUARDAR REALMENTE EN LA BASE DE DATOS
            $docente = Docente::create($data);

            \Log::info('Docente creado exitosamente con ID: ' . $docente->runDocente);

            return response()->json([
                'success' => true,
                'message' => 'Docente creado exitosamente.',
                'data' => $docente,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Errores de validación:', $e->errors());
            
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error al crear docente:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Docente $docente)
    {
        return response()->json($docente);
    }

    public function update(Request $request, Docente $docente)
    {
        try {
            $validated = $request->validate([
                'nombresDocente' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:docente,correo,'.$docente->runDocente.',runDocente',
                'fechaNacto' => 'required|date',
                'profesion' => 'required|string|max:100',
                'foto' => 'nullable|image|max:2048',
                'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        $data = collect($validated)->except([
            'foto', 'curriculum', 'certSuperInt', 
            'certRCP', 'certIAAS', 'acuerdo'
        ])->toArray();

        // Procesar archivos
        $fileFields = [
            'foto' => 'docentes/fotos',
            'curriculum' => 'docentes/curriculums',
            'certSuperInt' => 'docentes/certificados',
            'certRCP' => 'docentes/certificados',
            'certIAAS' => 'docentes/certificados',
            'acuerdo' => 'docentes/acuerdos'
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                if ($docente->$field) {
                    Storage::disk('public')->delete($docente->$field);
                }
                $data[$field] = $request->file($field)->store($path, 'public');
            }
        }

        $docente->update($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente actualizado exitosamente.',
                'data' => $docente,
            ]);
        }

        return redirect()->route('docentes.index')
            ->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(Docente $docente)
    {
        $fileFields = ['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'];
        
        foreach ($fileFields as $field) {
            if ($docente->$field) {
                Storage::disk('public')->delete($docente->$field);
            }
        }

        $docente->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente eliminado exitosamente.',
            ]);
        }

        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado exitosamente.');
    }
}
