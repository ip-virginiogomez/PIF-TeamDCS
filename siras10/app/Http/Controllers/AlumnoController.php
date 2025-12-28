<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CentroFormador;
use App\Models\EstadoVacuna;
use App\Models\SedeCarrera;
use App\Models\TipoVacuna;
use App\Models\VacunaAlumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

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

        $sortBy = request()->get('sort_by', 'fechaCreacion');
        $sortDirection = request()->get('sort_direction', 'desc');
        $search = request()->input('search');
        $filtroCentro = request()->input('centro_id');
        $filtroSedeCarrera = request()->input('sede_carrera_id');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'fechaCreacion';
        }

        $query = Alumno::query();
        $query->withCount('vacunas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('runAlumno', 'like', "%{$search}%")
                    ->orWhere('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidoPaterno', 'like', "%{$search}%")
                    ->orWhere('apellidoMaterno', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        if ($filtroSedeCarrera) {
            $query->whereHas('sedesCarreras', function ($q) use ($filtroSedeCarrera) {
                $q->where('alumno_sede_carrera.idSedeCarrera', $filtroSedeCarrera);
            });
        }

        if ($filtroCentro) {
            $query->whereHas('sedesCarreras.sede.centroFormador', function ($q) use ($filtroCentro) {
                $q->where('idCentroFormador', $filtroCentro);
            });
        }

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $alumnos = $query->paginate(10);

        $alumnos->appends([
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'centro_id' => $filtroCentro,
            'sede_carrera_id' => $filtroSedeCarrera,
        ]);

        $sedesCarreras = SedeCarrera::with(['sede', 'carrera'])->get();
        $centrosFormadores = CentroFormador::all();
        $tiposVacuna = TipoVacuna::orderBy('nombreVacuna', 'asc')->get();
        $estadosVacuna = EstadoVacuna::all();

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
            'sedesCarreras' => $sedesCarreras,
            'centrosFormadores' => $centrosFormadores,
            'tiposVacuna' => $tiposVacuna,
            'estadosVacuna' => $estadosVacuna,
        ]);
    }

    public function store(Request $request)
    {
        // Limpiar el RUN eliminando puntos
        $runLimpio = str_replace('.', '', $request->input('runAlumno'));
        $request->merge(['runAlumno' => $runLimpio]);

        $validator = Validator::make($request->all(), [
            'runAlumno' => [
                'required',
                'string',
                'regex:/^[0-9]+[-]?[0-9kK]{1}$/',
                Rule::unique('alumno', 'runAlumno')->whereNull('deleted_at'),
            ],
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required', 'email', 'max:50',
                Rule::unique('alumno', 'correo')->whereNull('deleted_at'),
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'idSedeCarrera' => 'required|integer|exists:sede_carrera,idSedeCarrera',
            // 'archivo_vacuna' => ... ELIMINADO
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $alumnoData = $request->except(['idSedeCarrera', '_token', 'archivo_vacuna']);
            $sedeCarreraId = $request->input('idSedeCarrera');

            if (empty($alumnoData['fechaCreacion'])) {
                $alumnoData['fechaCreacion'] = now()->format('Y-m-d');
            }

            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = storage_path('app/public/fotos/' . $filename);
                if (!file_exists(storage_path('app/public/fotos'))) {
                    mkdir(storage_path('app/public/fotos'), 0755, true);
                }
                Image::read($image)->scaleDown(width: 1024)->save($path, 90);
                $alumnoData['foto'] = 'fotos/' . $filename;
            }

            if ($request->hasFile('acuerdo')) {
                $alumnoData['acuerdo'] = $request->file('acuerdo')->store('acuerdos', 'public');
            }

            // Verificar si existe un alumno eliminado (Soft Delete)
            $alumno = Alumno::withTrashed()->where('runAlumno', $alumnoData['runAlumno'])->first();

            if ($alumno) {
                if ($alumno->trashed()) {
                    $alumno->restore();
                }
                $alumno->update($alumnoData);
            } else {
                $alumno = Alumno::create($alumnoData);
            }

            if ($sedeCarreraId) {
                // Usamos sync para asegurar que tenga la carrera seleccionada (limpiando anteriores si fue restaurado)
                $alumno->sedesCarreras()->sync([$sedeCarreraId]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alumno creado exitosamente.',
                'alumno' => $alumno,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function edit(Alumno $alumno)
    {
        $alumno = Alumno::findorfail($alumno->runAlumno);
        $sedesCarrerasDisponibles = SedeCarrera::with(['sede', 'carrera'])->get();
        $sedeCarreraActual = $alumno->sedesCarreras()->first();

        return response()->json([
            'alumno' => $alumno,
            'sedesCarrerasDisponibles' => $sedesCarrerasDisponibles,
            'sedeCarreraActual' => $sedeCarreraActual,
        ]);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validator = Validator::make($request->all(), [
            'runAlumno' => [
                'required',
                'string',
                'max:10',
                Rule::unique('alumno', 'runAlumno')->ignore($alumno->runAlumno, 'runAlumno')->whereNull('deleted_at'),
            ],
            'nombres' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required', 'email', 'max:50',
                Rule::unique('alumno', 'correo')->ignore($alumno->runAlumno, 'runAlumno')->whereNull('deleted_at'),
                'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/',
            ],
            'fechaNacto' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'acuerdo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'idSedeCarrera' => 'required|integer|exists:sede_carrera,idSedeCarrera',
            // 'archivo_vacuna' => ... ELIMINADO
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();
            $sedeCarreraId = $request->input('idSedeCarrera');
            unset($data['idSedeCarrera']);

            if ($request->hasFile('foto')) {
                if ($alumno->foto) {
                    Storage::disk('public')->delete($alumno->foto);
                }
                $image = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = storage_path('app/public/fotos/' . $filename);
                if (!file_exists(storage_path('app/public/fotos'))) {
                    mkdir(storage_path('app/public/fotos'), 0755, true);
                }
                Image::read($image)->scaleDown(width: 1024)->save($path, 90);
                $data['foto'] = 'fotos/' . $filename;
            }

            if ($request->hasFile('acuerdo')) {
                if ($alumno->acuerdo) {
                    Storage::disk('public')->delete($alumno->acuerdo);
                }
                $data['acuerdo'] = $request->file('acuerdo')->store('acuerdos', 'public');
            }

            $alumno->update($data);

            if ($sedeCarreraId) {
                $alumno->sedesCarreras()->sync([$sedeCarreraId]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alumno actualizado exitosamente.',
                'alumno' => $alumno,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function destroy(Alumno $alumno)
    {
        try {
            if ($alumno->foto) {
                Storage::disk('public')->delete($alumno->foto);
            }
            if ($alumno->acuerdo) {
                Storage::disk('public')->delete($alumno->acuerdo);
            }

            foreach ($alumno->vacunas as $vacuna) {
                // Verificar si la propiedad 'archivo' o 'documento' es la correcta
                // Según el modelo VacunaAlumno, el campo es 'documento'
                $archivo = $vacuna->documento ?? $vacuna->archivo;

                if ($archivo && Storage::disk('public')->exists($archivo)) {
                    Storage::disk('public')->delete($archivo);
                }
            }

            $alumno->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Estudiante eliminado.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'No se puede eliminar, tiene registros asociados.'], 409);
            }

            return redirect()->route('alumnos.index')->with('error', 'No se puede eliminar.');
        }

        return redirect()->route('alumnos.index')->with('success', 'Estudiante eliminado.');
    }

    public function getVacunas($runAlumno)
    {
        try {
            // 1. Verificamos si podemos consultar el modelo
            // Quitamos el orderBy temporalmente para aislar el error
            $vacunas = VacunaAlumno::where('runAlumno', $runAlumno)->get();

            // 2. Intentamos renderizar la vista
            return view('alumnos._lista_vacunas', compact('vacunas'))->render();

        } catch (\Exception $e) {
            // ESTO NOS DIRÁ EL ERROR EXACTO
            return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()], 500);
        }
    }

    public function storeVacuna(Request $request, $runAlumno)
    {
        $request->validate([
            'idTipoVacuna' => 'required|integer',
            'idEstadoVacuna' => 'required|integer',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'archivo.max' => 'El archivo es muy pesado. El límite es de 2MB.',
            'archivo.mimes' => 'Formato no válido. Solo se permite PDF, JPG o PNG.',
            'archivo.required' => 'Debes seleccionar un archivo.',
        ]);

        try {
            $path = $request->file('archivo')->store('vacunas_alumnos', 'public');

            VacunaAlumno::create([
                'runAlumno' => $runAlumno,
                'idTipoVacuna' => $request->idTipoVacuna,
                'idEstadoVacuna' => $request->idEstadoVacuna,
                'documento' => $path,
                'fechaSubida' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Vacuna agregada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error BD: '.$e->getMessage()], 500);
        }
    }

    public function destroyVacuna($idVacunaAlumno)
    {
        $vacuna = VacunaAlumno::findOrFail($idVacunaAlumno);

        if (Storage::disk('public')->exists($vacuna->documento)) {
            Storage::disk('public')->delete($vacuna->documento);
        }

        $vacuna->delete();

        return response()->json(['success' => true, 'message' => 'Vacuna eliminada']);
    }

    public function updateVacunaStatus(Request $request, $idVacunaAlumno)
    {
        $request->validate([
            'idEstadoVacuna' => 'required|integer|exists:estado_vacuna,idEstadoVacuna',
        ]);

        $vacuna = VacunaAlumno::findOrFail($idVacunaAlumno);
        $vacuna->idEstadoVacuna = $request->idEstadoVacuna;
        $vacuna->save();

        return response()->json(['success' => true, 'message' => 'Estado actualizado']);
    }

    public function getDocumentos($runAlumno)
    {
        $alumno = Alumno::with(['vacunas.tipoVacuna', 'vacunas.estadoVacuna'])
            ->findOrFail($runAlumno);

        return view('alumnos._lista_documentos_completa', compact('alumno'))->render();
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
