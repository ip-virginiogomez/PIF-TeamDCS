<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Centros de Salud') }}
            </h2>
            <button onclick="limpiarFormulario()" data-modal-target="centroSaludModal" data-modal-toggle="centroSaludModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Centro de Salud
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">Nombre del Centro</th>
                                    <th class="py-2 px-4 text-left">Dirección</th>
                                    <th class="py-2 px-4 text-left">Ciudad</th>
                                    <th class="py-2 px-4 text-left">Tipo</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($centrosSalud as $centro)
                                    <tr class="border-b" id="centro-{{ $centro->idCentroSalud }}">
                                        <td class="py-2 px-4">
                                            <div class="flex items-center space-x-3">
                                                <span class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 2a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <div>
                                                    <span class="font-medium">{{ $centro->nombreCentro }}</span>
                                                    <div class="text-sm text-gray-500">ID: {{ $centro->idCentroSalud }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4 text-sm">{{ $centro->direccion }}</td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $centro->ciudad->nombreCiudad ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $centro->tipoCentroSalud->acronimo ?? 'N/A' }}
                                            </span>
                                            <div class="text-xs text-gray-500">{{ $centro->tipoCentroSalud->nombreTipo ?? 'N/A' }}</div>
                                        </td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <button onclick="editarCentro({{ $centro->idCentroSalud }})" class="text-yellow-500 hover:text-yellow-700">
                                                Editar
                                            </button>
                                            <button onclick="eliminarCentro({{ $centro->idCentroSalud }})" class="text-red-500 hover:text-red-700">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center">No hay centros de salud registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Centro de Salud -->
    <div id="centroSaludModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                                Nuevo Centro de Salud
                            </h3>
                            <div class="mt-2">
                                <form id="centroSaludForm">
                                    @csrf
                                    <input type="hidden" id="centroId" name="centroId">
                                    <input type="hidden" id="method" name="_method" value="POST">
                                    
                                    <div class="mb-4">
                                        <label for="idTipoCentroSalud" class="block text-sm font-medium text-gray-700">Tipo de Centro *</label>
                                        <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idTipoCentroSalud" name="idTipoCentroSalud" required>
                                            <option value="">Seleccione un tipo</option>
                                            @foreach($tiposCentro as $tipo)
                                            <option value="{{ $tipo->idTipoCentroSalud }}">{{ $tipo->acronimo }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="error-idTipoCentroSalud"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="nombreCentro" class="block text-sm font-medium text-gray-700">Nombre del Centro *</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="nombreCentro" name="nombreCentro" required>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreCentro"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección *</label>
                                        <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="direccion" name="direccion" rows="3" required></textarea>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="error-direccion"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="idCiudad" class="block text-sm font-medium text-gray-700">Ciudad *</label>
                                        <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idCiudad" name="idCiudad" required>
                                            <option value="">Seleccione una ciudad</option>
                                            @foreach($ciudades as $ciudad)
                                            <option value="{{ $ciudad->idCiudad }}">{{ $ciudad->nombreCiudad }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-sm mt-1 hidden" id="error-idCiudad"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('centroSaludForm').dispatchEvent(new Event('submit'))" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="btnTexto">Guardar Centro</span>
                    </button>
                    <button type="button" onclick="cerrarModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/centro-salud.js'])
</x-app-layout>