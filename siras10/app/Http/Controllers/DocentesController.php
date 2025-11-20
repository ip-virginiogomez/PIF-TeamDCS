<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\Docente;
use App\Models\SedeCarrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $columnasDisponibles = ['runDocente', 'nombresDocente', 'apellidoPaterno', 'apellidoMaterno', 'correo', 'fechaNacto', 'fechaCreacion'];
        $sortBy = request()->get('sort_by', 'runDocente');
        $sortDirection = request()->get('sort_direction', 'asc');
        $search = request()->input('search');
        $filtroCentro = request()->input('centro_id');
        $filtroSedeCarrera = request()->input('sede_carrera_id');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'runDocente';
        }

        $query = Docente::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('runDocente', 'like', "%{$search}%")
                    ->orWhere('nombresDocente', 'like', "%{$search}%")
                    ->orWhere('apellidoPaterno', 'like', "%{$search}%")
                    ->orWhere('apellidoMaterno', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        if ($filtroSedeCarrera) {
            $query->whereHas('sedesCarreras', function ($q) use ($filtroSedeCarrera) {
                $q->where('docente_carrera.idSedeCarrera', $filtroSedeCarrera);
            });
        }

        if ($filtroCentro) {
            $query->whereHas('sedesCarreras.sede.centroFormador', function ($q) use ($filtroCentro) {
                $q->where('idCentroFormador', $filtroCentro);
            });
        }

        $docentes = $query->orderBy($sortBy, $sortDirection)->paginate(10);

        $docentes->appends([
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'centro_id' => $filtroCentro,
            'sede_carrera_id' => $filtroSedeCarrera,
        ]);

        $sedesCarreras = SedeCarrera::with('sede')->get();
        $centrosFormadores = CentroFormador::all();

        if (request()->ajax()) {
            return view('docentes._tabla', [
                'docentes' => $docentes,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        return view('docentes.index', [
            'docentes' => $docentes,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'sedesCarreras' => $sedesCarreras,
            'centrosFormadores' => $centrosFormadores,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idSedeCarrera' => 'required|integer|exists:sede_carrera,idSedeCarrera',
            'runDocente' => 'required|string|max:12|unique:docente,runDocente',
            'nombresDocente' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required',
                'email',
                'max:50',
                'unique:Docente,correo',
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'required|date',
            'profesion' => 'required|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'runDocente.required' => 'El RUN del docente es obligatorio.',
            'runDocente.max' => 'El RUN del docente no puede tener más de 12 caracteres.',
            'runDocente.unique' => 'El RUN del docente ya está en uso.',
            'nombresDocente.required' => 'El nombre del docente es obligatorio.',
            'nombresDocente.string' => 'El nombre del docente debe ser una cadena de texto.',
            'apellidoPaterno.required' => 'El apellido paterno del docente es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser una dirección válida.',
            'correo.regex' => 'El formato del correo electrónico es inválido.',
            'correo.unique' => 'El correo electrónico ya está en uso.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.max' => 'La foto no debe superar los 2MB.',
            'curriculum.mimes' => 'El currículum debe ser un archivo de tipo: pdf, doc, docx.',
            'curriculum.max' => 'El currículum no debe superar los 2MB.',
            'certSuperInt.mimes' => 'El certificado de Superintendencia debe ser un archivo de tipo: pdf, doc, docx.',
            'certSuperInt.max' => 'El certificado de Superintendencia no debe superar los 2MB.',
            'certRCP.mimes' => 'El certificado de RCP debe ser un archivo de tipo: pdf, doc, docx.',
            'certRCP.max' => 'El certificado de RCP no debe superar los 2MB.',
            'certIAAS.mimes' => 'El certificado de IAAS debe ser un archivo de tipo: pdf, doc, docx.',
            'certIAAS.max' => 'El certificado de IAAS no debe superar los 2MB.',
            'acuerdo.mimes' => 'El acuerdo debe ser un archivo de tipo: pdf, doc, docx.',
            'acuerdo.max' => 'El acuerdo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->except(['idSedeCarrera', 'foto', 'acuerdo', 'certIAAS', 'certRCP', 'certSuperInt', 'curriculum']);
            $sedeCarreraId = $request->input('idSedeCarrera');

            if ($sedeCarreraId) {
                $docente->sedesCarreras()->attach($sedeCarreraId);
            }
            if (empty($data['fechaCreacion'])) {
                $data['fechaCreacion'] = now()->format('Y-m-d');
            }

            if ($request->hasFile('foto')) {
                $rutafoto = $request->file('foto')->store('docentes/fotos', 'public');
                $data['foto'] = $rutafoto;
            }

            if ($request->hasFile('curriculum')) {
                $rutaCurriculum = $request->file('curriculum')->store('docentes/curriculums', 'public');
                $data['curriculum'] = $rutaCurriculum;
            }

            if ($request->hasFile('certSuperInt')) {
                $rutaCertSuperInt = $request->file('certSuperInt')->store('docentes/certificados', 'public');
                $data['certSuperInt'] = $rutaCertSuperInt;
            }

            if ($request->hasFile('certRCP')) {
                $rutaCertRCP = $request->file('certRCP')->store('docentes/certificados', 'public');
                $data['certRCP'] = $rutaCertRCP;
            }

            if ($request->hasFile('certIAAS')) {
                $rutaCertIAAS = $request->file('certIAAS')->store('docentes/certificados', 'public');
                $data['certIAAS'] = $rutaCertIAAS;
            }

            if ($request->hasFile('acuerdo')) {
                $rutaAcuerdo = $request->file('acuerdo')->store('docentes/acuerdos', 'public');
                $data['acuerdo'] = $rutaAcuerdo;
            }

            $docente = Docente::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Docente creado exitosamente.',
                'data' => $docente,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el docente: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit(Docente $docente)
    {
        $sedesCarrerasDisponibles = SedeCarrera::with('sede')->get();
        $sedeCarreraActual = $docente->sedesCarreras()->first();

        return response()->json([
            'docente' => $docente,
            'sedesCarrerasDisponibles' => $sedesCarrerasDisponibles,
            'idSedeCarreraActual' => $sedeCarreraActual ? $sedeCarreraActual->idSedeCarrera : null,
        ]);
    }

    public function update(Request $request, Docente $docente)
    {
        $validator = Validator::make($request->all(), [
            'runDocente' => 'required|string|max:12|unique:docente,runDocente,'.$docente->runDocente.',runDocente',
            'nombresDocente' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required',
                'email',
                'max:50',
                Rule::unique('Docente', 'correo')->ignore($docente->runDocente, 'runDocente'),
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'required|date',
            'profesion' => 'required|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'runDocente.required' => 'El RUN del docente es obligatorio.',
            'runDocente.max' => 'El RUN del docente no puede tener más de 12 caracteres.',
            'runDocente.unique' => 'El RUN del docente ya está en uso.',
            'nombresDocente.required' => 'El nombre del docente es obligatorio.',
            'nombresDocente.string' => 'El nombre del docente debe ser una cadena de texto.',
            'apellidoPaterno.required' => 'El apellido paterno del docente es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser una dirección válida.',
            'correo.regex' => 'El formato del correo electrónico es inválido.',
            'correo.unique' => 'El correo electrónico ya está en uso.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.max' => 'La foto no debe superar los 2MB.',
            'curriculum.mimes' => 'El currículum debe ser un archivo de tipo: pdf, doc, docx.',
            'curriculum.max' => 'El currículum no debe superar los 2MB.',
            'certSuperInt.mimes' => 'El certificado de Superintendencia debe ser un archivo de tipo: pdf, doc, docx.',
            'certSuperInt.max' => 'El certificado de Superintendencia no debe superar los 2MB.',
            'certRCP.mimes' => 'El certificado de RCP debe ser un archivo de tipo: pdf, doc, docx.',
            'certRCP.max' => 'El certificado de RCP no debe superar los 2MB.',
            'certIAAS.mimes' => 'El certificado de IAAS debe ser un archivo de tipo: pdf, doc, docx.',
            'certIAAS.max' => 'El certificado de IAAS no debe superar los 2MB.',
            'acuerdo.mimes' => 'El acuerdo debe ser un archivo de tipo: pdf, doc, docx.',
            'acuerdo.max' => 'El acuerdo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->except(['idSedeCarrera', 'foto', 'acuerdo', 'certIAAS', 'certRCP', 'certSuperInt', 'curriculum']);
            $sedeCarreraId = $request->input('idSedeCarrera');

            if ($request->hasFile('foto')) {
                if ($docente->foto) {
                    Storage::disk('public')->delete($docente->foto);
                }
                $rutafoto = $request->file('foto')->store('docentes/fotos', 'public');
                $data['foto'] = $rutafoto;
            }

            if ($request->hasFile('curriculum')) {
                if ($docente->curriculum) {
                    Storage::disk('public')->delete($docente->curriculum);
                }
                $rutaCurriculum = $request->file('curriculum')->store('docentes/curriculums', 'public');
                $data['curriculum'] = $rutaCurriculum;
            }

            if ($request->hasFile('certSuperInt')) {
                if ($docente->certSuperInt) {
                    Storage::disk('public')->delete($docente->certSuperInt);
                }
                $rutaCertSuperInt = $request->file('certSuperInt')->store('docentes/certificados', 'public');
                $data['certSuperInt'] = $rutaCertSuperInt;
            }

            if ($request->hasFile('certRCP')) {
                if ($docente->certRCP) {
                    Storage::disk('public')->delete($docente->certRCP);
                }
                $rutaCertRCP = $request->file('certRCP')->store('docentes/certificados', 'public');
                $data['certRCP'] = $rutaCertRCP;
            }

            if ($request->hasFile('certIAAS')) {
                if ($docente->certIAAS) {
                    Storage::disk('public')->delete($docente->certIAAS);
                }
                $rutaCertIAAS = $request->file('certIAAS')->store('docentes/certificados', 'public');
                $data['certIAAS'] = $rutaCertIAAS;
            }

            if ($request->hasFile('acuerdo')) {
                if ($docente->acuerdo) {
                    Storage::disk('public')->delete($docente->acuerdo);
                }
                $rutaAcuerdo = $request->file('acuerdo')->store('docentes/acuerdos', 'public');
                $data['acuerdo'] = $rutaAcuerdo;
            }

            $docente->update($data);

            if ($sedeCarreraId) {
                $docente->sedesCarreras()->sync([$sedeCarreraId]);
            } else {
                $docente->sedesCarreras()->detach();
            }

            return response()->json([
                'success' => true,
                'message' => 'Docente actualizado exitosamente.',
                'data' => $docente,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el docente: '.$e->getMessage(),
            ], 500);
        }
    }

    public function uploadDocument(Request $request, Docente $docente)
    {
        $allowedDocumentKeys = ['curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo', 'foto'];

        $uploadedDocKey = null;
        foreach ($allowedDocumentKeys as $key) {
            if ($request->hasFile($key)) {
                $uploadedDocKey = $key;
                break;
            }
        }

        if (! $uploadedDocKey) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha proporcionado ningún archivo para subir.',
            ], 400);
        }

        // Validar el archivo específico
        $validationRules = [
            $uploadedDocKey => ['required', 'file', 'max:2048'],
        ];

        // Ajustar reglas de MIME types según el tipo de documento
        if (in_array($uploadedDocKey, ['curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo'])) {
            $validationRules[$uploadedDocKey][] = 'mimes:pdf,doc,docx';
        } elseif ($uploadedDocKey === 'foto') {
            $validationRules[$uploadedDocKey][] = 'image';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación al subir el archivo.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file($uploadedDocKey);
            $oldFilePath = $docente->$uploadedDocKey;

            // Almacenar el nuevo archivo
            $path = $file->store("docentes/{$uploadedDocKey}s", 'public');

            // Actualizar la base de datos
            $docente->update([$uploadedDocKey => $path]);

            // Eliminar el archivo antiguo si existe y no es el placeholder (solo si el nuevo se subió con éxito)
            if ($oldFilePath && Storage::disk('public')->exists($oldFilePath) && ! str_contains($oldFilePath, 'placeholder.png')) {
                Storage::disk('public')->delete($oldFilePath);
            }

            return response()->json([
                'success' => true,
                'message' => "Documento {$uploadedDocKey} actualizado exitosamente.",
                'new_path' => $path,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la subida del documento: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Docente $docente)
    {
        // Eliminar archivos individualmente
        if ($docente->foto) {
            Storage::disk('public')->delete($docente->foto);
        }
        if ($docente->curriculum) {
            Storage::disk('public')->delete($docente->curriculum);
        }
        if ($docente->certSuperInt) {
            Storage::disk('public')->delete($docente->certSuperInt);
        }
        if ($docente->certRCP) {
            Storage::disk('public')->delete($docente->certRCP);
        }
        if ($docente->certIAAS) {
            Storage::disk('public')->delete($docente->certIAAS);
        }
        if ($docente->acuerdo) {
            Storage::disk('public')->delete($docente->acuerdo);
        }

        $docente->delete();

        // Respuesta JSON para AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente eliminado exitosamente.',
            ]);
        }

        // Redirección para requests normales
        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado exitosamente.');
    }

    public function showDocumentos(Docente $docente)
    {
        return view('docentes._documentos_lista', compact('docente'));
    }

    public function getSedesCarrerasByCentro(Request $request)
    {
        $centroId = $request->input('centro_id');

        $query = SedeCarrera::with('sede');

        if ($centroId) {
            $query->whereHas('sede', function ($q) use ($centroId) {
                $q->where('idCentroFormador', $centroId);
            });
        }

        $sedesCarreras = $query->get();

        return response()->json($sedesCarreras);
    }
}
