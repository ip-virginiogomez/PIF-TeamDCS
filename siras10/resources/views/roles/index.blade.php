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