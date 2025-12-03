<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            Gestión de Carreras por Sede
        </h2>
    </x-slot>

    {{-- CSRF TOKEN (OBLIGATORIO) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SELECCIÓN DE CENTRO Y SEDE --}}
            <div id="selection-container"
                data-centros="{{ json_encode($centrosFormadores) }}"
                data-carreras="{{ json_encode($carrerasBase) }}"
                data-coordinador-centro="{{ json_encode($coordinadorCentro) }}"
                class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Seleccionar Sede</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div></div>
                    <div></div>
                </div>
            </div>

            {{-- GESTIÓN DE CARRERAS --}}
            <div id="gestion-container" class="hidden">
                <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    Carreras en: <span id="sede-name-placeholder" class="text-blue-600"></span>
                </h3>
                @can('sede-carrera.create')
                <button type="button"
                        data-modal-target="crudModal"
                        class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Registrar Carrera
                </button>
                @endcan
            </div>

                <div id="tabla-container"></div>
            </div>
        </div>
    </div>
</div>

    {{-- MODAL CRUD --}}
    <div id="crudModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-900">Añadir Carrera</h3>
                    <button data-action="close-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="crudForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="idSede">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Perfil <span class="text-red-500">*</span>
                            </label>
                            <select name="idCarrera" id="idCarrera" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Seleccione un perfil --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre Carrera en esta sede
                            </label>
                            <input type="text" name="nombreSedeCarrera" id="nombreSedeCarrera"
                                placeholder="Opcional: deja vacío para usar el nombre base"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="codigoCarrera" id="codigoCarrera" required
                                placeholder="Ej: INF-2025"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" data-action="close-modal"
                                    class="px-5 py-2.5 bg-gray-300 hover:bg-gray-400 rounded-lg">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                <span id="btnTexto">Guardar Carrera</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL MALLA CURRICULAR --}}
<div id="mallaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
            {{-- Header del Modal --}}
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="mallaModalTitle" class="text-2xl font-bold text-gray-900">
                    Gestionar Malla Curricular
                </h3>
                <button 
                    type="button"
                    data-action="close-malla-modal" 
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Contenido del Modal --}}
            <div class="p-6">
                <form id="mallaForm" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <input type="hidden" id="mallaIdSedeCarrera" name="idSedeCarrera">

                    {{-- Campo de Año Académico --}}
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
                            max="2030"
                            placeholder="Ej: 2025"
                            value="{{ date('Y') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                    </div>

                    {{-- Campo de Nombre de la Malla --}}
                    <div>
                        <label for="nombreMalla" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Malla <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nombreMalla" 
                            name="nombre" 
                            required
                            placeholder="Ej: Malla Curricular Enfermería"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                    </div>

                    {{-- Campo de Archivo PDF --}}
                    <div>
                        <label for="archivoPdf" class="block text-sm font-medium text-gray-700 mb-2">
                            Archivo PDF <span class="text-red-500">*</span>
                            <span class="text-gray-500 text-xs font-normal">(Máximo 2MB)</span>
                        </label>
                        <div class="mt-1">
                            <input 
                                id="archivoPdf" 
                                name="documento" 
                                type="file" 
                                accept=".pdf" 
                                required 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 file:cursor-pointer border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                        </div>
                        <div id="archivoSeleccionado" class="mt-3 hidden">
                            <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm font-medium text-green-800">
                                    <span id="nombreArchivoSeleccionado"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button" 
                            data-action="close-malla-modal"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                            Guardar Malla
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
            {{-- Header del Modal --}}
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

            {{-- Contenedor del PDF --}}
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

    {{-- MODAL VER MALLAS CURRICULARES --}}
<div id="mallasListModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-900">Mallas Curriculares</h3>
                <button data-action="close-mallas-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Campo de Año para Filtro -->
                <div class="mb-6">
                    <label for="anioFiltroMallas" class="block text-sm font-medium text-gray-700 mb-2">
                        Filtrar por Año Académico (opcional)
                    </label>
                    <input type="number" 
                        id="anioFiltroMallas" 
                        min="2020" 
                        max="2030"
                        placeholder="Ej: 2025 (dejar vacío para ver todos)"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Contenedor de mallas -->
                <div id="mallas-container" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">
                        <p>Selecciona un año para ver las mallas curriculares</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @push('scripts')
        <script>
            // Hacer disponible globalmente
            window.sedeCarreraManager = null;
        </script>
        @vite(['resources/js/app.js'])
    @endpush
</x-app-layout>