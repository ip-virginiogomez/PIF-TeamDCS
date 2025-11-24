<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignación de Grupos a Campos Clínicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SECCIÓN 1: TABLA DE DISTRIBUCIONES (MAESTRO) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">1. Seleccione una Distribución (Cupos)</h3>
                    {{-- Aquí podrías poner el buscador de distribuciones --}}
                </div>
                <div class="p-0">
                    <div id="tabla-distribuciones-container">
                        @include('grupos._tabla_distribuciones', ['distribuciones' => $distribuciones])
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: TABLA DE GRUPOS (DETALLE) --}}
            {{-- Inicialmente oculta o vacía hasta que seleccionen arriba --}}
            <div id="seccion-grupos" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-800">
                        2. Grupos Asignados
                        <span id="titulo-distribucion-seleccionada" class="text-sm font-normal text-blue-600 ml-2"></span>
                    </h3>
                    
                    {{-- Botón para crear grupo en la distribución seleccionada --}}
                    <button id="btn-nuevo-grupo" data-distribucion-id="" class="bg-blue-600 hover:bg-blue-800 text-white text-sm font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i> Agregar Grupo
                    </button>
                </div>
                
                <div class="p-6">
                    <div id="tabla-grupos-container">
                        <p class="text-gray-500 text-center py-4">Cargando grupos...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE GRUPOS --}}
    <x-crud-modal modalId="grupoModal" formId="grupoForm" primaryKey="idGrupo" title="Grupo">
        <input type="hidden" id="idCupoDistribucion" name="idCupoDistribucion">

        <div class="mb-4">
            <label for="nombreGrupo" class="block text-sm font-medium text-gray-700">Nombre del Grupo *</label>
            <input type="text" id="nombreGrupo" name="nombreGrupo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required maxlength="45">
        </div>

        <div class="mb-4">
            <label for="idAsignatura" class="block text-sm font-medium text-gray-700">Asignatura *</label>
            <select id="idAsignatura" name="idAsignatura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione una asignatura...</option>
                @if(isset($listaAsignaturas))
                    @foreach($listaAsignaturas as $asig)
                        <option value="{{ $asig->idAsignatura }}">{{ $asig->nombreAsignatura }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-4">
            <label for="idDocenteCarrera" class="block text-sm font-medium text-gray-700">Docente Encargado *</label>
            <select id="idDocenteCarrera" name="idDocenteCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione un docente...</option>
                @if(isset($listaDocentesCarrera))
                    @foreach($listaDocentesCarrera as $dc)
                        <option value="{{ $dc->idDocenteCarrera }}">
                            {{ $dc->docente->nombresDocente ?? 'Sin Nombre' }} 
                            {{ $dc->docente->apellidoPaterno ?? '' }} 
                            ({{ $dc->sedeCarrera->nombreSedeCarrera ?? 'N/A' }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </x-crud-modal>

    @vite(['resources/js/app.js']) 
</x-app-layout>