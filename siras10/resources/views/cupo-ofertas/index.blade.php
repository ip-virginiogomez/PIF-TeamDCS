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
                        <option value="{{ $unidad->idUnidadClinica }}">{{ $unidad->nombreUnidad }}</option>
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
                <input type="number" id="cantCupos" name="cantCupos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
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