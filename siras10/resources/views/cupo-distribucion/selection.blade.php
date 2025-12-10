<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selección de Oferta para Distribución') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- COLUMNA 1: OFERTAS --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ofertas de Cupo</h3>
                        
                        {{-- Filtros Oferta --}}
                        <div class="mb-4">
                            <form id="search-form" class="flex flex-col gap-2" onsubmit="return false;">
                                {{-- Search Input --}}
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="text" id="search-input" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar oferta...">
                                    <button type="button" id="btn-clear-search" class="absolute inset-y-0 right-0 items-center pr-3 hidden text-gray-500 hover:text-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                
                                {{-- Selects --}}
                                <div class="grid grid-cols-2 gap-2">
                                    <select id="filter-periodo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                        <option value="">Periodo (Oferta)</option>
                                        @foreach($periodos as $periodo)
                                            <option value="{{ $periodo->idPeriodo }}">{{ $periodo->Año }}</option>
                                        @endforeach
                                    </select>
                                    <select id="filter-tipo-practica" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                        <option value="">Tipo Práctica</option>
                                        @foreach($tiposPractica as $tipo)
                                            <option value="{{ $tipo->idTipoPractica }}">{{ $tipo->nombrePractica }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <select id="filter-carrera" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                        <option value="">Carrera</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombreCarrera }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="btn-reset-filters" class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center justify-center" title="Limpiar filtros">
                                        Limpiar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="tabla-container">
                            @include('cupo-ofertas._tabla', ['cupoOfertas' => $cupoOfertas])
                        </div>
                    </div>
                </div>

                {{-- COLUMNA 2: DEMANDAS --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Demandas de Cupo</h3>
                        
                        {{-- Filtros Demanda --}}
                        <div class="mb-4">
                            <label for="periodo_filter" class="block text-sm font-medium text-gray-700">Filtrar por Periodo</label>
                            <select id="periodo_filter" name="periodo_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Todos</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->idPeriodo }}" {{ request('periodo_id') == $periodo->idPeriodo ? 'selected' : '' }}>
                                        {{ $periodo->Año }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="tabla-demandas">
                            @include('cupo-demanda._tabla', ['demandas' => $demandas])
                        </div>
                        
                        <div class="mt-4">
                            {{ $demandas->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Ver Horario (Required for Offer Table) --}}
    <div id="verHorarioModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden items-center justify-center w-full h-full bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="relative w-full max-w-2xl max-h-full p-4">
            <div class="relative bg-white rounded-lg shadow-xl">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">Horario Detallado</h3>
                    <button type="button" onclick="cerrarModalVerHorario()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-6" id="verHorarioContent"></div>
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button onclick="cerrarModalVerHorario()" type="button" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verHorario(horarios) {
            const modal = document.getElementById('verHorarioModal');
            const content = document.getElementById('verHorarioContent');
            if (!horarios || horarios.length === 0) {
                content.innerHTML = '<p class="text-gray-500">No hay horarios definidos.</p>';
            } else {
                const grupos = {};
                horarios.forEach(h => {
                    const key = `${h.horaEntrada}-${h.horaSalida}`;
                    if (!grupos[key]) grupos[key] = { entrada: h.horaEntrada, salida: h.horaSalida, dias: [] };
                    grupos[key].dias.push(h.diaSemana);
                });
                let html = '<div class="relative overflow-x-auto"><table class="w-full text-sm text-left text-gray-500"><thead class="text-xs text-gray-700 uppercase bg-gray-50"><tr><th scope="col" class="px-6 py-3">Días</th><th scope="col" class="px-6 py-3">Hora Inicio</th><th scope="col" class="px-6 py-3">Hora Fin</th></tr></thead><tbody>';
                Object.values(grupos).forEach(g => {
                    const diasStr = g.dias.map(d => d.charAt(0).toUpperCase() + d.slice(1)).join(', ');
                    const entrada = g.entrada ? g.entrada.substring(0, 5) : 'N/A';
                    const salida = g.salida ? g.salida.substring(0, 5) : 'N/A';
                    html += `<tr class="bg-white border-b"><td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${diasStr}</td><td class="px-6 py-4">${entrada}</td><td class="px-6 py-4">${salida}</td></tr>`;
                });
                html += '</tbody></table></div>';
                content.innerHTML = html;
            }
            modal.classList.remove('hidden');
        }
        function cerrarModalVerHorario() {
            document.getElementById('verHorarioModal').classList.add('hidden');
        }
    </script>

    @vite(['resources/js/app.js'])
</x-app-layout>
