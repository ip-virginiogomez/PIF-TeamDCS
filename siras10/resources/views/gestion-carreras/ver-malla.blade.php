<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                Malla Curricular: {{ $malla->nombre }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('malla.descargar', $malla->idMallaSedeCarrera) }}" 
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Descargar PDF
                </a>
                <button onclick="window.close()" 
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cerrar
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Información de la Malla --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Carrera</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $malla->sedeCarrera->carrera->nombreCarrera }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Código</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $malla->sedeCarrera->codigoCarrera }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Año Académico</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $malla->mallaCurricular->anio }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha de Subida</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $malla->fechaSubida ? \Carbon\Carbon::parse($malla->fechaSubida)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Visor de PDF --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Documento PDF</h3>
                </div>
                <div class="p-4" style="height: calc(100vh - 300px); min-height: 600px;">
                    <iframe 
                        src="{{ $documentoUrl }}" 
                        class="w-full h-full border-0 rounded-lg"
                        frameborder="0"
                        title="Visor de PDF - {{ $malla->nombre }}">
                        <p>Tu navegador no soporta iframes. 
                            <a href="{{ $documentoUrl }}" target="_blank" class="text-blue-600 hover:underline">
                                Haz clic aquí para abrir el PDF
                            </a>
                        </p>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>