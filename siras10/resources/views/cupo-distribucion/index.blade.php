<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                {{-- Botón Volver --}}
                <a href="{{ route('cupo-ofertas.index') }}" 
                    class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                
                <div>
                    {{-- CAMBIO: Color de texto del header igual a Sede --}}
                    <h2 class="font-semibold text-xl text-black leading-tight">
                        Distribución de Cupos
                    </h2>
                {{-- CAMBIO: Color de texto del párrafo --}}
                <p class="text-sm text-gray-600"> 
                    Estás distribuyendo <strong>{{ $oferta->cantCupos }}</strong> cupos para
                    <strong>{{ $oferta->carrera->nombreCarrera ?? 'N/A' }}</strong>
                    ({{ $oferta->tipoPractica->nombrePractica ?? 'N/A' }})
                    
                    {{-- LÍNEA AÑADIDA: --}}
                    en <strong>{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'Centro Desconocido' }}</strong>
                    ({{ $oferta->unidadClinica->nombreUnidad ?? 'Unidad Desc.' }})
                </p>
                </div>
            </div>

            {{-- Botón "Nuevo" (Estilo Sede) --}}
            <button
                data-modal-target="distribucionModal"
                data-modal-toggle="distribucionModal"
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Asignar a Centro
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Barra azul de Cupos Restantes --}}
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Cupos Restantes por Distribuir: </strong>
                        <span class="block sm:inline" id="cupos-restantes-display">{{ $cuposRestantes }}</span>
                        <div id="distribucion-data" data-cupos-restantes="{{ $cuposRestantes }}"></div>
                    </div>

                    {{-- Contenedor de la Tabla --}}
                    <div id="tabla-container">
                        @include('cupo-distribucion._tabla', [
                            'distribuciones' => $distribuciones,
                            'oferta' => $oferta,
                            'sortBy' => $sortBy ?? 'idCupoDistribucion', // Añadimos defaults
                            'sortDirection' => $sortDirection ?? 'asc' // Añadimos defaults
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal (Tu código estaba perfecto) --}}
    <x-crud-modal
        modalId="distribucionModal"
        formId="distribucionForm"
        title="Gestión de Distribución"
        primaryKey="idCupoDistribucion">

        <input type="hidden" name="idCupoOferta" value="{{ $oferta->idCupoOferta }}">

        {{-- Campo Sede/Carrera --}}
        <div class="mb-4">
            <label for="idSedeCarrera" class="block text-sm font-medium text-gray-700">Sede / Carrera (Demanda) *</label>
            <select id="idSedeCarrera" name="idSedeCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Seleccione Sede / Carrera...</option>
                @foreach ($sedeCarreras as $sedeCarrera)
                    @php
                        $demanda = $sedeCarrera->cupoDemandas->first();
                        $infoSolicitud = $demanda ? " (Solicitados: {$demanda->cuposSolicitados})" : "";
                    @endphp
                    <option value="{{ $sedeCarrera->idSedeCarrera }}">
                        {{ $sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }} 
                        ({{ $sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }}) 
                        - {{ $sedeCarrera->nombreSedeCarrera ?: ($sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.') }}
                        {{ $infoSolicitud }}
                    </option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idSedeCarrera"></div>
        </div>

        {{-- Campo Cantidad --}}
        <div class="mb-4">
            <label for="cantCupos" class="block text-sm font-medium text-gray-700">Cantidad de Cupos a Asignar *</label>
            <input type="number" id="cantCupos" name="cantCupos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-cantCupos"></div>
        </div>

    </x-crud-modal>
    
    @vite(['resources/js/app.js'])
</x-app-layout>