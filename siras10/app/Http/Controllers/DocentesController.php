<?php

namespace App\Http\Controllers;

use App\Models\Docente;
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

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'runDocente';
        }

        $query = Docente::query();

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $docentes = $query->paginate(10);

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
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            $data = $request->all();

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
        $docente = Docente::findorfail($docente->runDocente);

        return response()->json($docente);
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
            $data = $validator->validated();

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
}
