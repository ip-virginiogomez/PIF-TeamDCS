@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Archivos de: ') }}{{ $sedeCarrera->sede->centroFormador->nombreCentroFormador }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Sede {{ $sedeCarrera->sede->nombreSede }} · Carrera {{ $sedeCarrera->carrera->nombreCarrera }} · Código {{ $sedeCarrera->codigoCarrera }}
                </p>
            </div>
            <a href="{{ route('sede-carrera.index', [
                'centro' => $sedeCarrera->sede->centroFormador->idCentroFormador,
                'sede' => $sedeCarrera->sede->idSede
            ]) }}"
            class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </x-slot>

    {{-- CSRF TOKEN (OBLIGATORIO) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BLOQUE MALLAS --}}
            <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Mallas Curriculares</h3>
                            <p class="text-sm text-gray-500">Historial de mallas asociadas a esta carrera/sede.</p>
                        </div>
                        @can('sede-carrera.create')
                        <button type="button"
                                data-open-malla
                                data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Nueva Malla
                        </button>
                        @endcan
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left font-bold">Nombre</th>
                                    <th class="py-2 px-4 text-left font-bold">Año</th>
                                    <th class="py-2 px-4 text-left font-bold">Fecha Subida</th>
                                    <th class="py-2 px-4 text-left font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mallas as $malla)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">
                                            <span>{{ $malla->nombre }}</span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $malla->mallaCurricular->anio }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex space-x-2">
                                                <button type="button"
                                                        data-action="preview-malla" 
                                                        data-url="{{ asset('storage/' . $malla->documento) }}"
                                                        data-title="{{ $malla->nombre }}"
                                                        data-info="Año {{ $malla->mallaCurricular->anio }} · {{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}"
                                                        title="Ver documento"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                @can('sede-carrera.update')
                                                <button type="button"
                                                        data-action="edit-malla" 
                                                        data-id="{{ $malla->idMallaSedeCarrera }}"
                                                        data-nombre="{{ $malla->nombre }}"
                                                        data-anio="{{ $malla->mallaCurricular->anio }}"
                                                        data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                                                        title="Editar malla"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                @endcan

                                                @can('sede-carrera.delete')
                                                <button type="button"
                                                        data-action="delete-malla" 
                                                        data-id="{{ $malla->idMallaSedeCarrera }}"
                                                        data-nombre="{{ $malla->nombre }}"
                                                        title="Eliminar malla"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                @endcan

                                                <a href="{{ route('sede-carrera.malla.descargar', $malla->idMallaSedeCarrera) }}"
                                                   title="Descargar documento"
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-file-pdf text-4xl text-gray-300 mb-2"></i>
                                                <span>No hay mallas registradas.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($mallas->hasPages())
                    <div class="mt-4">
                        {{ $mallas->appends(['asignaturas_page' => request('asignaturas_page')])->links() }}
                    </div>
                    @endif
                </div>
            </section>

            {{-- BLOQUE PROGRAMAS DE ASIGNATURA --}}
            <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Asignaturas</h3>
                            <p class="text-sm text-gray-500">Gestión de asignaturas y programas para la carrera.</p>
                        </div>
                        <button 
                            type="button"
                            data-open-asignatura
                            data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                            class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Nueva Asignatura
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left font-bold">Asignatura</th>
                                    <th class="py-2 px-4 text-left font-bold">Código</th>
                                    <th class="py-2 px-4 text-left font-bold">Semestre</th>
                                    <th class="py-2 px-4 text-left font-bold">Tipo Práctica</th>
                                    <th class="py-2 px-4 text-left font-bold">Programa Vigente</th>
                                    <th class="py-2 px-4 text-left font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($asignaturas as $asignatura)
                                    @php
                                        $programa = $asignatura->programa;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-2 px-4">
                                            <span>{{ $asignatura->nombreAsignatura }}</span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $asignatura->codAsignatura ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="text-sm">{{ $asignatura->Semestre ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-2 px-4 text-sm">
                                            {{ $asignatura->tipoPractica->nombrePractica ?? 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4 text-center">
                                            @if ($programa)
                                                <button
                                                    type="button"
                                                    data-action="preview-programa"
                                                    data-url="{{ asset('storage/' . $programa->documento) }}"
                                                    data-title="Programa de {{ $asignatura->nombreAsignatura }}"
                                                    data-asignatura="{{ $asignatura->nombreAsignatura }}"
                                                    data-fecha="{{ $programa->fechaSubida ? $programa->fechaSubida->format('d/m/Y') : 'N/A' }}"
                                                    title="Ver programa vigente"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-sm text-gray-400">Sin programa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex space-x-2">
                                                {{-- Botón Ver todos los programas --}}
                                                <button
                                                    type="button"
                                                    data-action="view-all-programas"
                                                    data-id="{{ $asignatura->idAsignatura }}"
                                                    title="Ver historial de programas"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-indigo-500 hover:bg-indigo-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                                    </svg>
                                                </button>
                                            {{-- Modal para historial de programas --}}
                                            <div id="programasModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
                                                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full overflow-auto max-h-[80vh] m-4">
                                                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                                        <h3 class="text-lg font-semibold text-gray-900">Historial de Programas</h3>
                                                        <button data-action="close-programas-modal" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div id="programasModalContent" class="p-6">
                                                        <!-- Aquí se carga la tabla de programas -->
                                                    </div>
                                                </div>
                                            </div>

                                                {{-- Botón Editar Asignatura --}}
                                                <button
                                                    type="button"
                                                    data-action="edit-asignatura"
                                                    data-id="{{ $asignatura->idAsignatura }}"
                                                    data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                                                    title="Editar asignatura"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>

                                                {{-- Botón Eliminar Asignatura --}}
                                                <button
                                                    type="button"
                                                    data-action="delete-asignatura"
                                                    data-id="{{ $asignatura->idAsignatura }}"
                                                    data-nombre="{{ $asignatura->nombreAsignatura }}"
                                                    title="Eliminar asignatura"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>

                                                {{-- Botón Subir/Actualizar Programa --}}
                                                <button
                                                    type="button"
                                                    data-open-programa
                                                    data-id-asignatura="{{ $asignatura->idAsignatura }}"
                                                    data-nombre-asignatura="{{ $asignatura->nombreAsignatura }}"
                                                    data-programa-nombre="{{ $programa->nombre ?? '' }}"
                                                    title="{{ $programa ? 'Actualizar programa' : 'Subir programa' }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                    </svg>
                                                </button>

                                                @if ($programa)
                                                <a href="{{ route('sede-carrera.asignaturas.programa.download', $asignatura->idAsignatura) }}"
                                                title="Descargar programa"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-book text-4xl text-gray-300 mb-2"></i>
                                                <span>No hay asignaturas registradas.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($asignaturas->hasPages())
                    <div class="mt-4">
                        {{ $asignaturas->appends(['mallas_page' => request('mallas_page')])->links() }}
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- MODAL MALLA CURRICULAR --}}
    <div id="mallaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl m-4 max-h-[90vh] overflow-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 id="mallaModalTitle" class="text-lg font-semibold text-gray-900">Gestionar Malla Curricular</h3>
                    <button
                        type="button"
                        data-action="close-malla-modal"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="mallaForm" method="POST" action="{{ route('sede-carrera.malla.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="hidden" id="mallaIdSedeCarrera" name="idSedeCarrera" value="{{ $sedeCarrera->idSedeCarrera }}">

                        <div>
                            <label for="anioMalla" class="block text-sm font-medium text-gray-700 mb-2">
                                Año Académico <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="anioMalla"
                                name="anio"
                                required
                                min="2020"
                                max="2035"
                                value="{{ date('Y') }}"
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div>
                            <label for="nombreMalla" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Malla <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="nombreMalla"
                                name="nombre"
                                required
                                placeholder="Ej: Malla Curricular 2025"
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div>
                            <label for="archivoPdf" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo PDF <span class="text-red-500">*</span>
                                <span class="text-gray-500 text-xs font-normal">(Máximo 2MB)</span>
                            </label>
                            <input
                                id="archivoPdf"
                                name="documento"
                                type="file"
                                accept=".pdf"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border rounded-lg focus:ring-2 focus:ring-green-500"
                            >
                            <div id="archivoSeleccionado" class="mt-3 hidden">
                                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span id="nombreArchivoSeleccionado"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                data-action="close-malla-modal"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-5 py-2.5">
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-6 py-2.5">
                                Guardar Malla
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    {{-- MODAL ASIGNATURA --}}
    <div id="asignaturaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl m-4 max-h-[90vh] overflow-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 id="asignaturaModalTitle" class="text-lg font-semibold text-gray-900">Crear Asignatura</h3>
                    <button
                        type="button"
                        data-action="close-asignatura-modal"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="asignaturaForm" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" id="asignaturaIdSedeCarrera" name="idSedeCarrera" value="{{ $sedeCarrera->idSedeCarrera }}">

                        <div>
                            <label for="nombreAsignatura" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Asignatura <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="nombreAsignatura"
                                name="nombreAsignatura"
                                required
                                placeholder="Ej: Enfermería Médico-Quirúrgica"
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div>
                            <label for="codAsignatura" class="block text-sm font-medium text-gray-700 mb-2">
                                Código de Asignatura <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="codAsignatura"
                                name="codAsignatura"
                                required
                                placeholder="Ej: ENF-301"
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <div>
                            <label for="Semestre" class="block text-sm font-medium text-gray-700 mb-2">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="Semestre"
                                name="Semestre"
                                required
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">-- Seleccione semestre --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">Semestre {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="idTipoPractica" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Práctica <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="idTipoPractica"
                                name="idTipoPractica"
                                required
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">-- Seleccione tipo de práctica --</option>
                                @foreach (\App\Models\TipoPractica::orderBy('nombrePractica')->get() as $tipo)
                                    <option value="{{ $tipo->idTipoPractica }}">{{ $tipo->nombrePractica }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                data-action="close-asignatura-modal"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-5 py-2.5">
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-6 py-2.5">
                                Guardar Asignatura
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    {{-- MODAL PROGRAMA --}}
    <div id="programaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl m-4 max-h-[90vh] overflow-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 id="programaModalTitle" class="text-lg font-semibold text-gray-900">Subir Programa</h3>
                        <p id="programaAsignaturaName" class="text-sm text-gray-500 mt-1"></p>
                    </div>
                    <button
                        type="button"
                        data-action="close-programa-modal"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="programaForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label for="programaArchivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo PDF <span class="text-red-500">*</span>
                                <span class="text-gray-500 text-xs font-normal">(Máximo 2MB)</span>
                            </label>
                            <input
                                id="programaArchivo"
                                name="documento"
                                type="file"
                                accept=".pdf"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            >
                            <div id="programaArchivoSeleccionado" class="mt-3 hidden">
                                <div class="flex items-center p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-sm text-indigo-800">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span id="programaNombreArchivo"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                data-action="close-programa-modal"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-5 py-2.5">
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-6 py-2.5">
                                Guardar Programa
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    {{-- MODAL VISTA PREVIA PDF --}}
    <div id="pdfModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl m-4 max-h-[90vh] overflow-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex-1">
                        <h3 id="pdfModalTitle" class="text-lg font-semibold text-gray-900">Vista Previa de Documento</h3>
                        <p id="pdfModalInfo" class="text-sm text-gray-500 mt-1"></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a id="pdfDownloadBtn" 
                            href="#" 
                            download
                            class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg px-4 py-2">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar
                        </a>
                        <button 
                            type="button"
                            data-action="close-pdf-modal" 
                            class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg overflow-hidden h-[calc(90vh-200px)] min-h-[500px]">
                        <iframe 
                            id="pdfViewer"
                            src="" 
                            class="w-full h-full border-0"
                            frameborder="0"
                            title="Visor de PDF">
                            <p class="p-4 text-center text-gray-600">
                                Tu navegador no soporta iframes. 
                                <a id="pdfFallbackLink" href="#" target="_blank" class="text-blue-600 hover:underline">
                                    Haz clic aquí para abrir el PDF
                                </a>
                            </p>
                        </iframe>
                    </div>
                </div>
        </div>
    </div>
    @push('scripts')
        @vite(['resources/js/app.js'])
    @endpush
</x-app-layout>