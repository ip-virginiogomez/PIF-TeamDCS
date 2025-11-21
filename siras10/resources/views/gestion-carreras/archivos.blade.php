@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-black leading-tight">
                    Archivos de: {{ $sedeCarrera->sede->centroFormador->nombreCentroFormador }}
                </h2>
                <p class="text-sm text-gray-600">
                    Sede {{ $sedeCarrera->sede->nombreSede }} · Carrera {{ $sedeCarrera->carrera->nombreCarrera }}
                    · Código {{ $sedeCarrera->codigoCarrera }}
                </p>
            </div>

            <a href="{{ route('sede-carrera.index') }}"
               class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </x-slot>

    {{-- CSRF TOKEN (OBLIGATORIO) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- BLOQUE MALLAS --}}
            <section class="bg-white shadow-sm rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Mallas curriculares</h3>
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
                                <th class="py-2 px-4 text-left">Nombre</th>
                                <th class="py-2 px-4 text-left">Año</th>
                                <th class="py-2 px-4 text-left">Fecha Subida</th>
                                <th class="py-2 px-4 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mallas as $malla)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4">
                                        <span class="font-medium">{{ $malla->nombre }}</span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $malla->mallaCurricular->anio }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <span class="text-sm text-gray-600">{{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-2 px-4 flex space-x-2">
                                        <button type="button"
                                                data-action="preview-malla" 
                                                data-url="{{ Storage::url($malla->documento) }}"
                                                data-title="{{ $malla->nombre }}"
                                                data-info="Año {{ $malla->mallaCurricular->anio }} · {{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}"
                                                class="text-blue-500 hover:text-blue-700"
                                                title="Ver documento">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>

                                        @can('sede-carrera.update')
                                        <button type="button"
                                                data-action="edit-malla" 
                                                data-id="{{ $malla->idMallaSedeCarrera }}"
                                                data-nombre="{{ $malla->nombre }}"
                                                data-anio="{{ $malla->mallaCurricular->anio }}"
                                                data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                                                class="text-yellow-500 hover:text-yellow-700"
                                                title="Editar malla">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        @endcan

                                        @can('sede-carrera.delete')
                                        <button type="button"
                                                data-action="delete-malla" 
                                                data-id="{{ $malla->idMallaSedeCarrera }}"
                                                data-nombre="{{ $malla->nombre }}"
                                                class="text-red-500 hover:text-red-700"
                                                title="Eliminar malla">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                        @endcan

                                        <a href="{{ route('sede-carrera.malla.descargar', $malla->idMallaSedeCarrera) }}"
                                           class="text-gray-500 hover:text-gray-700"
                                           title="Descargar documento">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-file-pdf text-6xl mb-4"></i>
                                            <p class="text-lg">Aún no existen mallas registradas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- BLOQUE PROGRAMAS DE ASIGNATURA --}}
            <section class="bg-white shadow-sm rounded-xl p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Programas de asignaturas</h3>
                    <p class="text-sm text-gray-500">Sube el programa vigente de cada asignatura en esta carrera/sede.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Asignatura</th>
                                <th class="py-2 px-4 text-left">Programa Vigente</th>
                                <th class="py-2 px-4 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asignaturas as $asignatura)
                                @php
                                    $programa = $asignatura->programa;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4">
                                        <div>
                                            <span class="font-medium">{{ $asignatura->nombreAsignatura }}</span>
                                            <p class="text-xs text-gray-500">Código: {{ $asignatura->codigoAsignatura ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4">
                                        @if ($programa)
                                            <div>
                                                <span class="text-sm text-green-700">{{ $programa->nombre }}</span>
                                                <span class="block text-xs text-gray-500">{{ optional($programa->fechaSubida)->format('d/m/Y') ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">Sin programa cargado</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 flex space-x-2">
                                        <button type="button"
                                                data-open-programa
                                                data-id-asignatura="{{ $asignatura->idAsignatura }}"
                                                data-nombre-asignatura="{{ $asignatura->nombreAsignatura }}"
                                                data-programa-nombre="{{ $programa->nombre ?? '' }}"
                                                class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-upload"></i> {{ $programa ? 'Actualizar' : 'Subir' }}
                                        </button>

                                        @if ($programa)
                                        <a href="{{ route('sede-carrera.asignaturas.programa.download', $asignatura->idAsignatura) }}"
                                           class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-book text-6xl mb-4"></i>
                                            <p class="text-lg">No hay asignaturas asociadas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    {{-- MODAL MALLA CURRICULAR (reutilizado desde index.blade.php) --}}
    <div id="mallaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 id="mallaModalTitle" class="text-2xl font-bold text-gray-900">Gestionar Malla Curricular</h3>
                    <button
                        type="button"
                        data-action="close-malla-modal"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100"
                    >
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
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm"
                            >
                                Guardar Malla
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PROGRAMA DE ASIGNATURA --}}
    <div id="programaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 id="programaModalTitle" class="text-2xl font-bold text-gray-900">Subir programa</h3>
                    <button
                        type="button"
                        data-action="close-programa-modal"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="programaForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <p id="programaAsignaturaName" class="text-sm text-gray-500"></p>

                        <div>
                            <label for="programaNombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del programa <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="programaNombre"
                                name="nombre"
                                required
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>

                        <div>
                            <label for="programaArchivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo PDF <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="file"
                                id="programaArchivo"
                                name="documento"
                                accept=".pdf"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                data-action="close-programa-modal"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm"
                            >
                                Guardar programa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL VISTA PREVIA PDF --}}
    <div id="pdfPreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl" style="max-height: 90vh;">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-green-50 to-blue-50">
                    <div class="flex-1">
                        <h3 id="pdfModalTitle" class="text-2xl font-bold text-gray-900">Vista Previa de Malla Curricular</h3>
                        <p id="pdfModalInfo" class="text-sm text-gray-600 mt-1"></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a id="pdfDownloadBtn" 
                            href="#" 
                            download
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar
                        </a>
                        <button 
                            type="button"
                            data-action="close-pdf-modal" 
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg overflow-hidden" style="height: calc(90vh - 200px); min-height: 500px;">
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
    </div>
    @push('scripts')
        @vite(['resources/js/app.js'])
    @endpush
</x-app-layout>