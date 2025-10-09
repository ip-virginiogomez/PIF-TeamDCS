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

        return view('docentes.index', compact('docentes'));
    }

    public function store(Request $request)
    {
        try {
            // ✅ IGUAL QUE ALUMNOS: Validar directamente y capturar excepción
            $request->validate([
                'runDocente' => 'required|string|max:12|unique:docente,runDocente',
                'nombresDocente' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:docente,correo',
                'fechaNacto' => 'required|date', // ✅ CAMBIO: required como en alumnos
                'profesion' => 'required|string|max:100',
                'foto' => 'nullable|image|max:2048',
                'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ IGUAL QUE ALUMNOS: Manejar errores de validación
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // ✅ IGUAL QUE ALUMNOS: Usar except() para excluir archivos
        $data = $request->except(['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo']);

        // ✅ IGUAL QUE ALUMNOS: Manejar archivos individualmente con hasFile()
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('docentes/fotos', 'public');
        }

        if ($request->hasFile('curriculum')) {
            $data['curriculum'] = $request->file('curriculum')->store('docentes/curriculums', 'public');
        }

        if ($request->hasFile('certSuperInt')) {
            $data['certSuperInt'] = $request->file('certSuperInt')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('certRCP')) {
            $data['certRCP'] = $request->file('certRCP')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('certIAAS')) {
            $data['certIAAS'] = $request->file('certIAAS')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('acuerdo')) {
            $data['acuerdo'] = $request->file('acuerdo')->store('docentes/acuerdos', 'public');
        }

        // ✅ IGUAL QUE ALUMNOS: Agregar fecha de creación
        $data['fechaCreacion'] = now();

        // ✅ IGUAL QUE ALUMNOS: Crear registro
        $docente = Docente::create($data);

        // ✅ IGUAL QUE ALUMNOS: Respuesta JSON para AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente creado exitosamente.',
                'data' => $docente,
            ]);
        }

        // ✅ IGUAL QUE ALUMNOS: Redirección para requests normales
        return redirect()->route('docentes.index')
            ->with('success', 'Docente creado exitosamente.');
    }

    public function edit(Docente $docente)
    {
        // ✅ IGUAL QUE ALUMNOS: Simple retorno JSON
        return response()->json($docente);
    }

    public function update(Request $request, Docente $docente)
    {
        try {
            // ✅ IGUAL QUE ALUMNOS: Validar directamente y capturar excepción
            $request->validate([
                'runDocente' => 'required|string|max:12|unique:docente,runDocente,'.$docente->runDocente.',runDocente',
                'nombresDocente' => 'required|string|max:100',
                'apellidoPaterno' => 'required|string|max:45',
                'apellidoMaterno' => 'nullable|string|max:45',
                'correo' => 'required|email|max:50|unique:docente,correo,'.$docente->runDocente.',runDocente',
                'fechaNacto' => 'required|date', // ✅ CAMBIO: required como en alumnos
                'profesion' => 'required|string|max:100',
                'foto' => 'nullable|image|max:2048',
                'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certSuperInt' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certRCP' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certIAAS' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ IGUAL QUE ALUMNOS: Manejar errores de validación
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // ✅ IGUAL QUE ALUMNOS: Usar except() para excluir archivos
        $data = $request->except(['foto', 'curriculum', 'certSuperInt', 'certRCP', 'certIAAS', 'acuerdo']);

        // ✅ IGUAL QUE ALUMNOS: Manejar archivos individualmente
        if ($request->hasFile('foto')) {
            if ($docente->foto) {
                Storage::disk('public')->delete($docente->foto);
            }
            $data['foto'] = $request->file('foto')->store('docentes/fotos', 'public');
        }

        if ($request->hasFile('curriculum')) {
            if ($docente->curriculum) {
                Storage::disk('public')->delete($docente->curriculum);
            }
            $data['curriculum'] = $request->file('curriculum')->store('docentes/curriculums', 'public');
        }

        if ($request->hasFile('certSuperInt')) {
            if ($docente->certSuperInt) {
                Storage::disk('public')->delete($docente->certSuperInt);
            }
            $data['certSuperInt'] = $request->file('certSuperInt')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('certRCP')) {
            if ($docente->certRCP) {
                Storage::disk('public')->delete($docente->certRCP);
            }
            $data['certRCP'] = $request->file('certRCP')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('certIAAS')) {
            if ($docente->certIAAS) {
                Storage::disk('public')->delete($docente->certIAAS);
            }
            $data['certIAAS'] = $request->file('certIAAS')->store('docentes/certificados', 'public');
        }

        if ($request->hasFile('acuerdo')) {
            if ($docente->acuerdo) {
                Storage::disk('public')->delete($docente->acuerdo);
            }
            $data['acuerdo'] = $request->file('acuerdo')->store('docentes/acuerdos', 'public');
        }

        // ✅ IGUAL QUE ALUMNOS: Actualizar
        $docente->update($data);

        // ✅ IGUAL QUE ALUMNOS: Respuesta JSON para AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente actualizado exitosamente.',
                'data' => $docente,
            ]);
        }

        // ✅ IGUAL QUE ALUMNOS: Redirección para requests normales
        return redirect()->route('docentes.index')
            ->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(Docente $docente)
    {
        // ✅ ADAPTADO DE ALUMNOS: Eliminar archivos individualmente
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

        // ✅ IGUAL QUE ALUMNOS: Respuesta JSON para AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente eliminado exitosamente.',
            ]);
        }

        // ✅ IGUAL QUE ALUMNOS: Redirección para requests normales
        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado exitosamente.');
    }
}
