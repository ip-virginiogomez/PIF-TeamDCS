<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Distribución de Cupos
                </h2>
                <p class="text-sm text-gray-600">
                    Estás distribuyendo <strong>{{ $oferta->cantCupos }}</strong> cupos para
                    <strong>{{ $oferta->carrera->nombreCarrera ?? 'N/A' }}</strong>
                    ({{ $oferta->tipoPractica->nombrePractica ?? 'N/A' }})
                    en <strong>{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'N/A' }}</strong>
                    (Período: {{ $oferta->periodo->Año ?? 'N/A' }}).
                </p>
            </div>

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

                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Cupos Restantes por Distribuir: </strong>
                        <span class="block sm:inline" id="cupos-restantes-display">{{ $cuposRestantes }}</span>
                        <div id="distribucion-data" data-cupos-restantes="{{ $cuposRestantes }}"></div>
                    </div>

                    <div id="tabla-container">
                        @include('cupo-distribucion._tabla', [
                            'distribuciones' => $distribuciones,
                            'oferta' => $oferta
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EL ÚNICO MODAL (Corregido) --}}
    <x-crud-modal
        modalId="distribucionModal"
        formId="distribucionForm"
        title="Gestión de Distribución" {{-- Atributo title presente --}}
        primaryKey="idCupoDistribucion">

        {{-- CAMPO OCULTO: ID de la Oferta Padre --}}
        <input type="hidden" name="idCupoOferta" value="{{ $oferta->idCupoOferta }}">

        {{-- Campo Sede/Carrera (Usa idSedeCarrera y $sedesCarreras) --}}
        <div class="mb-4">
            <label for="idSedeCarrera" class="block text-sm font-medium text-gray-700">Sede / Carrera *</label>
            <select id="idSedeCarrera" name="idSedeCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Seleccione Sede/Carrera</option>
                {{-- Bucle sobre la variable correcta $sedesCarreras --}}
                @foreach($sedesCarreras as $sc)
                    <option value="{{ $sc->idSedeCarrera }}">
                        {{ $sc->sede->nombreSede ?? 'Sede Desc.' }}
                        ({{ $sc->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }})
                        - {{ $sc->carrera->nombreCarrera ?? 'Carrera Desc.' }}
                    </option>
                @endforeach
            </select>
            {{-- ID del error correcto --}}
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idSedeCarrera"></div>
        </div>

        {{-- Campo Cantidad (Usa cantCupos) --}}
        <div class="mb-4">
            <label for="cantCupos" class="block text-sm font-medium text-gray-700">Cantidad de Cupos a Asignar *</label>
            <input type="number" id="cantCupos" name="cantCupos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-cantCupos"></div>
        </div>

    </x-crud-modal>
    @vite(['resources/js/app.js'])
</x-app-layout>