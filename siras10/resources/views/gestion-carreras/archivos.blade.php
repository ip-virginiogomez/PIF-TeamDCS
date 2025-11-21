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

            <div class="flex flex-wrap gap-3">
                <a 
                href="{{ route('sede-carrera.index', ['id' => $sedeCarrera->idSedeCarrera]) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow"
                >
                Volver
                </a>
            </div>
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
                    <button 
                        type="button"
                        data-open-malla
                        data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                        class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg text-sm font-medium"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Malla
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse ($mallas as $malla)
                        <article class="border border-gray-100 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">{{ $malla->nombre }}</h4>
                                <p class="text-sm text-gray-500">
                                    Año {{ $malla->mallaCurricular->anio }} · Subida el {{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2 items-center">
                                {{-- Botón Ver --}}
                                <button 
                                    type="button"
                                    data-action="preview-malla" 
                                    data-url="{{ Storage::url($malla->documento) }}"
                                    data-title="{{ $malla->nombre }}"
                                    data-info="Año {{ $malla->mallaCurricular->anio }} · Subida el {{ optional($malla->fechaSubida)->format('d/m/Y') ?? 'N/A' }}"
                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition duration-200 focus:outline-none"
                                    title="Ver documento"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                {{-- Botón Editar --}}
                                <button 
                                    type="button"
                                    data-action="edit-malla" 
                                    data-id="{{ $malla->idMallaSedeCarrera }}"
                                    data-nombre="{{ $malla->nombre }}"
                                    data-anio="{{ $malla->mallaCurricular->anio }}"
                                    data-id-sede-carrera="{{ $sedeCarrera->idSedeCarrera }}"
                                    class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-100 rounded-full transition duration-200 focus:outline-none"
                                    title="Editar malla"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Botón Eliminar --}}
                                <button 
                                    type="button"
                                    data-action="delete-malla" 
                                    data-id="{{ $malla->idMallaSedeCarrera }}"
                                    data-nombre="{{ $malla->nombre }}"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition duration-200 focus:outline-none"
                                    title="Eliminar malla"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                {{-- Botón Descargar --}}
                                <a
                                    href="{{ route('sede-carrera.malla.descargar', $malla->idMallaSedeCarrera) }}"
                                    class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition duration-200 focus:outline-none"
                                    title="Descargar documento"
                                    download {{-- --}}
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-center text-gray-500 py-6">Aún no existen mallas registradas.</p>
                    @endforelse
                </div>
            </section>

            {{-- BLOQUE PROGRAMAS DE ASIGNATURA --}}
            <section class="bg-white shadow-sm rounded-xl p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Programas de asignaturas</h3>
                    <p class="text-sm text-gray-500">Sube el programa vigente de cada asignatura en esta carrera/sede.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-left text-sm font-medium text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Asignatura</th>
                                <th class="px-4 py-3">Programa vigente</th>
                                <th class="px-4 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse ($asignaturas as $asignatura)
                                @php
                                    $programa = $asignatura->programa;
                                @endphp
                                <tr>
                                    <td class="px-4 py-4">
                                        <span class="font-semibold text-gray-900">{{ $asignatura->nombreAsignatura }}</span>
                                        <p class="text-xs text-gray-500">Código: {{ $asignatura->codigoAsignatura ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if ($programa)
                                            <div class="text-green-700">
                                                {{ $programa->nombre }}
                                                <span class="block text-xs text-gray-500">
                                                    Última subida: {{ optional($programa->fechaSubida)->format('d/m/Y') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">Sin programa cargado</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                type="button"
                                                class="px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg"
                                                data-open-programa
                                                data-id-asignatura="{{ $asignatura->idAsignatura }}"
                                                data-nombre-asignatura="{{ $asignatura->nombreAsignatura }}"
                                                data-programa-nombre="{{ $programa->nombre ?? '' }}"
                                            >
                                                {{ $programa ? 'Actualizar' : 'Subir' }} programa
                                            </button>

                                            @if ($programa)
                                                <a
                                                    href="{{ route('sede-carrera.asignaturas.programa.download', $asignatura->idAsignatura) }}"
                                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg"
                                                >
                                                    Descargar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        No hay asignaturas asociadas a esta carrera/sede.
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
        @vite(['resources/js/sede-carrera.js'])
    @endpush
</x-app-layout>