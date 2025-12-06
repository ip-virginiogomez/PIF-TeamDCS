<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Roles') }}
            </h2>
            <button onclick="window.rolManager.showCreateModal()" 
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Rol
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
                                placeholder="Buscar por nombre de rol...">
                            <button type="button" id="btn-clear-search" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                                <svg class="w-4 h-4 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="tabla-container">
                        @include('roles._tabla', ['roles' => $roles, 'sortBy' => $sortBy ?? 'name', 'sortDirection' => $sortDirection ?? 'asc'])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar rol -->
    <x-crud-modal 
        modalId="rolModal" 
        formId="rolForm" 
        title="Rol">
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
            <input type="text" name="name" id="name" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                required>
            <p id="error-name" class="text-red-500 text-xs mt-1 hidden"></p>
        </div>

    </x-crud-modal>
</x-app-layout>