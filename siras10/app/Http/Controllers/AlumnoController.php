<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importante para manejar archivos

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::paginate(10);

        return view('admin.alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('admin.alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno',
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'required|string|max:45',
            'correo' => 'required|email|max:50|unique:Alumno,correo',
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|max:2048', // Valida que sea imagen y no pese más de 2MB
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // Valida que sea pdf/doc y no pese más de 5MB
        ]);

        $data = $request->except(['foto', 'acuerdo']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alumnos/fotos', 'public');
        }

        if ($request->hasFile('acuerdo')) {
            $data['acuerdo'] = $request->file('acuerdo')->store('alumnos/acuerdos', 'public');
        }

        $data['fechaCreacion'] = now();

        Alumno::create($data);

        return redirect()->route('alumnos.index')
            ->with('success', 'Estudiante creado exitosamente.');
    }

    public function edit(Alumno $alumno)
    {
        return view('admin.alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'runAlumno' => 'required|string|max:10|unique:Alumno,runAlumno,'.$alumno->runAlumno.',runAlumno',
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'required|string|max:45',
            'correo' => 'required|email|max:50|unique:Alumno,correo,'.$alumno->runAlumno.',runAlumno',
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

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

        return redirect()->route('alumnos.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }
}
