<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Demanda de Cupos') }}
            </h2>
            <button data-modal-target="demandaModal"
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nueva Demanda
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Filtros --}}
                    <div class="mb-4">
                        <label for="periodo_filter" class="block text-sm font-medium text-gray-700">Filtrar por Periodo</label>
                        <select id="periodo_filter" name="periodo_id" class="mt-1 block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->idPeriodo }}" {{ $periodoId == $periodo->idPeriodo ? 'selected' : '' }}>
                                    {{ $periodo->Año }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="tabla-demandas">
                        @include('cupo-demanda._tabla')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-crud-modal 
        modalId="demandaModal" 
        formId="demandaForm" 
        title="Nueva Demanda"
        primaryKey="idDemandaCupo">
        
        <input type="hidden" name="_method" id="method" value="POST">
        <input type="hidden" name="idPeriodo" value="{{ $periodoId }}">
        
        {{-- Contenedor para campos individuales (Edición) --}}
        <div id="single-edit-fields" class="hidden">
            <div class="mb-4">
                <label for="idSedeCarrera" class="block text-sm font-medium text-gray-700">Sede - Carrera</label>
                <select name="idSedeCarrera" id="idSedeCarrera" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Seleccione...</option>
                    @foreach($sedesCarreras as $sc)
                        <option value="{{ $sc->idSedeCarrera }}">
                            {{ $sc->sede->centroFormador->nombreCentroFormador }} - {{ $sc->sede->nombreSede }} - {{ $sc->carrera->nombreCarrera }}
                        </option>
                    @endforeach
                </select>
                <div id="error-idSedeCarrera" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label for="asignatura" class="block text-sm font-medium text-gray-700">Asignatura</label>
                <select name="asignatura" id="asignatura" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Seleccione Sede-Carrera primero...</option>
                </select>
                <div id="error-asignatura" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label for="cuposSolicitados" class="block text-sm font-medium text-gray-700">Cupos Solicitados</label>
                <input type="number" name="cuposSolicitados" id="cuposSolicitados" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="1">
                <div id="error-cuposSolicitados" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>
        </div>

        {{-- Contenedor para tabla dinámica (Creación masiva) --}}
        <div id="bulk-create-fields">
            <div class="flex justify-end mb-2">
                <button type="button" id="addRowBtn" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-2 rounded">
                    + Agregar Fila
                </button>
            </div>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full bg-white" id="demandasTable">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-3 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-900">Sede - Carrera</th>
                            <th class="px-3 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-900">Asignatura</th>
                            <th class="px-3 py-2 text-left whitespace-nowrap w-24 text-xs font-medium text-gray-900">Cupos</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Filas dinámicas --}}
                    </tbody>
                </table>
            </div>
        </div>
    </x-crud-modal>

    {{-- Template para fila de tabla --}}
    <template id="demandaRowTemplate">
        <tr class="border-b hover:bg-gray-50">
            <td class="px-3 py-2">
                <select name="demandas[INDEX][idSedeCarrera]" class="sede-carrera-select block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs" required>
                    <option value="">Seleccione...</option>
                    @foreach($sedesCarreras as $sc)
                        <option value="{{ $sc->idSedeCarrera }}">
                            {{ $sc->sede->centroFormador->nombreCentroFormador }} - {{ $sc->sede->nombreSede }} - {{ $sc->carrera->nombreCarrera }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2">
                <select name="demandas[INDEX][asignatura]" class="asignatura-select block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs" required>
                    <option value="">Seleccione Sede-Carrera...</option>
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="number" name="demandas[INDEX][cuposSolicitados]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs" required min="1">
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" class="text-red-600 hover:text-red-900 remove-row-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </td>
        </tr>
    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/app.js'])
    @endpush
</x-app-layout>
