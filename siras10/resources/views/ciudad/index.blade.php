<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Gestión de Ciudades') }}
            </h2>
            <button 
                data-modal-target="ciudadModal" 
                data-modal-toggle="ciudadModal" 
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nueva Ciudad
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Barra de búsqueda --}}
                    <div class="mb-4 flex justify-between items-center">
                        <div class="relative w-full max-w-md">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="search-input" 
                                class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Buscar por nombre de ciudad...">
                            <button type="button" id="btn-clear-search" class="absolute inset-y-0 right-0 items-center pr-3 hidden">
                                <svg class="w-4 h-4 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="tabla-container">
                        @include('ciudad._tabla', [
                            'ciudades' => $ciudades,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                </div>
            </div>
        </div>
    </div>    <x-crud-modal 
        modalId="ciudadModal" 
        formId="ciudadForm" 
        title="Gestión de Ciudad"
        primaryKey="idCiudad">
        
        {{-- Nombre de la Ciudad --}}
        <div class="mb-4">
            <label for="nombreCiudad" class="block text-sm font-medium text-gray-700">Nombre de la Ciudad *</label>
            <input type="text" id="nombreCiudad" name="nombreCiudad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ej: Los Ángeles" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreCiudad"></div>
        </div>

    </x-crud-modal>

    {{-- Modal de Centros Asociados --}}
    <div id="modalCentrosAsociados" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="cerrarModalCentros()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] flex flex-col">
                <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-white" id="modalTitulo">Centros Asociados</h3>
                </div>
                <div class="p-6">
                    <div id="listaCentros" class="space-y-2 max-h-96 overflow-y-auto">
                        <!-- Los centros se cargarán aquí -->
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end">
                    <button onclick="cerrarModalCentros()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded transition-colors duration-150">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verCentrosAsociados(id, nombre) {
            const modal = document.getElementById('modalCentrosAsociados');
            const titulo = document.getElementById('modalTitulo');
            const lista = document.getElementById('listaCentros');
            
            titulo.textContent = `Centros Asociados - ${nombre}`;
            
            const centrosDiv = document.getElementById(`centros-${id}`);
            const centros = centrosDiv.querySelectorAll('[data-centro]');
            
            lista.innerHTML = '';
            centros.forEach(centro => {
                const div = document.createElement('div');
                div.className = 'flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200';
                div.innerHTML = `
                    <svg class="w-5 h-5 text-sky-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-gray-800 font-medium">${centro.textContent}</span>
                `;
                lista.appendChild(div);
            });
            
            modal.classList.remove('hidden');
        }
        
        function cerrarModalCentros() {
            const modal = document.getElementById('modalCentrosAsociados');
            modal.classList.add('hidden');
        }
    </script>

    @vite(['resources/js/app.js'])
</x-app-layout>
