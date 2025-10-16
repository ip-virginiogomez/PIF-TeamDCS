<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importante para manejar archivos

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
        try {
            $request->validate([
                'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno',
                'nombres' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:Alumno,correo',
                'fechaNacto' => 'nullable|date',
                'foto' => 'nullable|image|max:2048', // Valida que sea imagen y no pese más de 2MB
                'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // Valida que sea pdf/doc y no pese más de 5MB
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

        $data = $request->except(['foto', 'acuerdo']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alumnos/fotos', 'public');
        }

        if ($request->hasFile('acuerdo')) {
            $data['acuerdo'] = $request->file('acuerdo')->store('alumnos/acuerdos', 'public');
        }

        $data['fechaCreacion'] = now();

        $alumno = Alumno::create($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estudiante creado exitosamente.',
                'data' => $alumno,
            ]);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Estudiante creado exitosamente.');
    }

    public function edit(Alumno $alumno)
    {
        return response()->json($alumno);
    }

    public function update(Request $request, Alumno $alumno)
    {
        try {
            $request->validate([
                'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno,'.$alumno->runAlumno.',runAlumno',
                'nombres' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:Alumno,correo,'.$alumno->runAlumno.',runAlumno',
                'fechaNacto' => 'nullable|date',
                'foto' => 'nullable|image|max:2048',
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

        $data = $request->except(['foto', 'acuerdo']);

        if ($request->hasFile('foto')) {
            if ($alumno->foto) {
                Storage::disk('public')->delete($alumno->foto);
            }
            $data['foto'] = $request->file('foto')->store('alumnos/fotos', 'public');
        }

        if ($request->hasFile('acuerdo')) {
            if ($alumno->acuerdo) {
                Storage::disk('public')->delete($alumno->acuerdo);
            }
            $data['acuerdo'] = $request->file('acuerdo')->store('alumnos/acuerdos', 'public');
        }

        $alumno->update($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estudiante actualizado exitosamente.',
                'data' => $alumno,
            ]);
        }

        return redirect()->route('alumnos.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
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
