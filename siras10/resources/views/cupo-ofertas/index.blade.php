<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Oferta de Cupos') }}
            </h2>
            <button data-modal-target="cupoOfertaModal" data-modal-toggle="cupoOfertaModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nueva Oferta
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <form id="search-form" class="flex flex-col md:flex-row gap-2" onsubmit="return false;">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" id="search-input" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar por unidad clínica o centro de salud...">
                                <button type="button" id="btn-clear-search" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="w-full md:w-48">
                                <select id="filter-periodo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">Todos los Periodos</option>
                                    @foreach($periodos as $periodo)
                                        <option value="{{ $periodo->idPeriodo }}">{{ $periodo->Año }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-48">
                                <select id="filter-tipo-practica" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">Todas las Prácticas</option>
                                    @foreach($tiposPractica as $tipo)
                                        <option value="{{ $tipo->idTipoPractica }}">{{ $tipo->nombrePractica }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-48">
                                <select id="filter-carrera" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="">Todas las Carreras</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombreCarrera }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center">
                                <button type="button" id="btn-reset-filters" class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2" title="Limpiar filtros">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="sr-only">Limpiar filtros</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="tabla-container">
                        {{-- La tabla se carga aquí --}}
                        @include('cupo-ofertas._tabla', ['cupoOfertas' => $cupoOfertas])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar Ofertas de Cupo --}}
    <x-crud-modal 
        modalId="cupoOfertaModal" 
        formId="cupoOfertaForm" 
        title="Gestión de Oferta"
        primaryKey="idCupoOferta"
        closeFunction="cerrarModalCupoOferta()"> {{-- 1. Le decimos al modal cómo cerrarse --}}
        
        {{-- Contenido del formulario (sin cambios) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="idPeriodo" class="block text-sm font-medium text-gray-700">Período *</label>
                <select id="idPeriodo" name="idPeriodo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Seleccione</option>
                    @foreach($periodos as $periodo)
                        <option value="{{ $periodo->idPeriodo }}">{{ $periodo->Año }}</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-idPeriodo"></div>
            </div>
            <div>
                <label for="idUnidadClinica" class="block text-sm font-medium text-gray-700">Unidad Clínica *</label>
                <select id="idUnidadClinica" name="idUnidadClinica" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Seleccione</option>
                    @foreach($unidadesClinicas as $unidad)
                        <option value="{{ $unidad->idUnidadClinica }}">{{ $unidad->nombreUnidad }} ({{ $unidad->centroSalud->nombreCentro ?? 'N/A' }})</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-idUnidadClinica"></div>
            </div>
            <div>
                <label for="idTipoPractica" class="block text-sm font-medium text-gray-700">Tipo de Práctica *</label>
                <select id="idTipoPractica" name="idTipoPractica" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Seleccione</option>
                    @foreach($tiposPractica as $tipo)
                        <option value="{{ $tipo->idTipoPractica }}">{{ $tipo->nombrePractica }}</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-idTipoPractica"></div>
            </div>
            <div>
                <label for="idCarrera" class="block text-sm font-medium text-gray-700">Carrera *</label>
                <select id="idCarrera" name="idCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Seleccione</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombreCarrera }}</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-idCarrera"></div>
            </div>
            <div>
                <label for="cantCupos" class="block text-sm font-medium text-gray-700">Cantidad de Cupos *</label>
                <input type="number" id="cantCupos" name="cantCupos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1" max="99" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-cantCupos"></div>
            </div>
            <div>
                <label for="fechaEntrada" class="block text-sm font-medium text-gray-700">Fecha de Entrada *</label>
                <input type="date" id="fechaEntrada" name="fechaEntrada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaEntrada"></div>
            </div>
            <div>
                <label for="fechaSalida" class="block text-sm font-medium text-gray-700">Fecha de Salida *</label>
                <input type="date" id="fechaSalida" name="fechaSalida" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaSalida"></div>
            </div>
            <div>
                <label for="horaEntrada" class="block text-sm font-medium text-gray-700">Hora de Entrada *</label>
                <input type="time" id="horaEntrada" name="horaEntrada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-horaEntrada"></div>
            </div>
            <div>
                <label for="horaSalida" class="block text-sm font-medium text-gray-700">Hora de Salida *</label>
                <input type="time" id="horaSalida" name="horaSalida" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <div class="text-red-500 text-sm mt-1 hidden" id="error-horaSalida"></div>
            </div>
        </div>
        
        {{-- 2. AÑADIMOS EL FOOTER CON LOS BOTONES --}}
        <x-slot name="footer">
            <button type="button" onclick="cerrarModalCupoOferta()" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Guardar
            </button>
        </x-slot>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>