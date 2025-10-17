<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:alumnos.read')->only('index');
        $this->middleware('can:alumnos.create')->only(['create', 'store']);
        $this->middleware('can:alumnos.update')->only(['edit', 'update']);
        $this->middleware('can:alumnos.delete')->only('destroy');
    }

    public function index()
    {
        $columnasDisponibles = ['runAlumno', 'nombres', 'apellidoPaterno', 'apellidoMaterno', 'correo', 'fechaNacto', 'fechaCreacion'];

        $sortBy = request()->get('sort_by', 'runAlumno');
        $sortDirection = request()->get('sort_direction', 'asc');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'runAlumno';
        }

        $query = Alumno::query();

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $alumnos = $query->paginate(10);

        if (request()->ajax()) {
            return view('alumnos._tabla', [
                'alumnos' => $alumnos,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        return view('alumnos.index', [
            'alumnos' => $alumnos,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno',
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required',
                'email',
                'max:50',
                'unique:Alumno,correo',
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'runAlumno.required' => 'El campo RUN es obligatorio.',
            'runAlumno.unique' => 'El RUN ya está registrado.',
            'nombres.required' => 'El campo Nombres es obligatorio.',
            'apellidoPaterno.required' => 'El campo Apellido Paterno es obligatorio.',
            'correo.required' => 'El campo Correo es obligatorio.',
            'correo.email' => 'El campo Correo debe ser una dirección de correo válida.',
            'correo.unique' => 'El correo ya está registrado.',
            'foto.image' => 'El archivo debe ser una imagen (jpeg, png, jpg).',
            'foto.max' => 'La imagen no debe superar los 2MB.',
            'acuerdo.file' => 'El acuerdo debe ser un archivo válido.',
            'acuerdo.mimes' => 'El acuerdo debe ser un archivo de tipo: pdf, doc, docx.',
            'acuerdo.max' => 'El acuerdo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->all();

            if (empty($data['fechaCreacion'])) {
                $data['fechaCreacion'] = now()->format('Y-m-d');
            }

            if ($request->hasFile('foto')) {
                $rutaFoto['foto'] = $request->file('foto')->store('fotos_alumnos', 'public');
                $data['foto'] = $rutaFoto;
            }

            $alumno = Alumno::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Alumno creado exitosamente.',
                'alumno' => $alumno,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el alumno: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit(Alumno $alumno)
    {
        $alumno = Alumno::findorfail($alumno->runAlumno);

        return response()->json($alumno);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validator = Validator::make($request->all(), [
            'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno,'.$alumno->runAlumno.',runAlumno',
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required',
                'email',
                'max:50',
                Rule::unique('Alumno', 'correo')->ignore($alumno->runAlumno, 'runAlumno'),
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'runAlumno.required' => 'El campo RUN es obligatorio.',
            'runAlumno.unique' => 'El RUN ya está registrado.',
            'nombres.required' => 'El campo Nombres es obligatorio.',
            'apellidoPaterno.required' => 'El campo Apellido Paterno es obligatorio.',
            'correo.required' => 'El campo Correo es obligatorio.',
            'correo.email' => 'El campo Correo debe ser una dirección de correo válida.',
            'correo.unique' => 'El correo ya está registrado.',
            'foto.image' => 'El archivo debe ser una imagen (jpeg, png, jpg).',
            'foto.max' => 'La imagen no debe superar los 2MB.',
            'acuerdo.file' => 'El acuerdo debe ser un archivo válido.',
            'acuerdo.mimes' => 'El acuerdo debe ser un archivo de tipo: pdf, doc, docx.',
            'acuerdo.max' => 'El acuerdo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $alumno = Alumno::findorfail($alumno->runAlumno);

            if ($request->hasFile('foto')) {
                if ($alumno->foto) {
                    Storage::disk('public')->delete($alumno->foto);
                }
                $rutaFoto = $request->file('foto')->store('fotos_alumnos', 'public');
                $alumno->foto = $rutaFoto;
            }

            $alumno->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Alumno actualizado exitosamente.',
                'alumno' => $alumno,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el alumno: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Alumno $alumno)
    {
        if ($alumno->foto) {
            Storage::disk('public')->delete($alumno->foto);
        }
        if ($alumno->acuerdo) {
            Storage::disk('public')->delete($alumno->acuerdo);
        }

        $alumno->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estudiante eliminado exitosamente.',
            ]);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }
}
