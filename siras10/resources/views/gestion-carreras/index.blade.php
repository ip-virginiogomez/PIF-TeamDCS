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
                        <h3 class="text-xl font-bold">
                            Carreras en: <span id="sede-name-placeholder" class="text-indigo-600"></span>
                        </h3>
                        <button type="button"
                                data-modal-target="crudModal"
                                class="inline-flex items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Añadir Carrera
                        </button>
                    </div>

                    <div id="tabla-container" class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-3">Selecciona una sede para ver sus carreras</p>
                    </div>
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

    @push('scripts')
        <script>
            // Hacer disponible globalmente
            window.sedeCarreraManager = null;
        </script>
        @vite(['resources/js/sede-carrera.js'])
    @endpush
</x-app-layout>