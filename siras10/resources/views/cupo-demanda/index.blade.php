<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Demanda de Cupos') }}
            </h2>
            <button data-modal-target="demandaModal"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nueva Demanda
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Filtro por Periodo --}}
                    <div class="mb-4">
                        <label for="periodo_filter" class="block text-sm font-medium text-gray-700">Filtrar por Periodo</label>
                        <select id="periodo_filter" name="periodo_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->idPeriodo }}" {{ $periodoId == $periodo->idPeriodo ? 'selected' : '' }}>
                                    {{ $periodo->nombrePeriodo }}
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
    <div id="demandaModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="demandaModal" data-action="close-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white" id="modalTitle">Nueva Demanda</h3>
                    <form id="demandaForm" action="{{ route('cupo-demandas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="idPeriodo" value="{{ $periodoId }}">
                        
                        <div class="mb-4">
                            <label for="idSedeCarrera" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sede - Carrera</label>
                            <select name="idSedeCarrera" id="idSedeCarrera" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
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
                            <label for="cuposSolicitados" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cupos Solicitados</label>
                            <input type="number" name="cuposSolicitados" id="cuposSolicitados" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required min="1">
                            <div id="error-cuposSolicitados" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <span id="btnTexto">Guardar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/cupo-demanda.js'])
    @endpush
</x-app-layout>
