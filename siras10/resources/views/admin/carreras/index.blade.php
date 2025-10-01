<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Carreras') }}
            </h2>
            @can('carreras.create')
                <button onclick="limpiarFormularioCarrera()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Crear Nueva Carrera
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border-green-400 text-green-700 border-l-4 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">Nombre</th>
                                    <th class="py-2 px-4 text-left">Fecha de Creación</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carreras as $carrera)
                                    <tr id="carrera-{{ $carrera->idCarrera }}" class="border-b">
                                        <td class="py-2 px-4">{{ $carrera->nombreCarrera }}</td>
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($carrera->fechaCreacion)->format('d-m-Y') }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            @can('carreras.update')
                                                <button onclick="editarCarrera({{ $carrera->idCarrera }})" class="text-yellow-500 hover:text-yellow-700">Editar</button>
                                            @endcan
                                            @can('carreras.delete')
                                                <button onclick="eliminarCarrera({{ $carrera->idCarrera }})" class="text-red-500 hover:text-red-700">Eliminar</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center">No hay carreras registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $carreras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar carreras -->
    <x-crud-modal 
        modalId="carreraModal" 
        formId="carreraForm" 
        primaryKey="idCarrera"
        title="Nueva Carrera"
        buttonText="Guardar Carrera"
        closeFunction="cerrarModalCarrera()">
        
        <!-- Nombre de la Carrera -->
        <div class="mb-4">
            <label for="nombreCarrera" class="block text-sm font-medium text-gray-700">Nombre de la Carrera *</label>
            <input type="text" 
                   id="nombreCarrera" 
                   name="nombreCarrera" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   required>
            <div id="error-nombreCarrera" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

    </x-crud-modal>

    @vite(['resources/js/carreras.js'])
</x-app-layout>