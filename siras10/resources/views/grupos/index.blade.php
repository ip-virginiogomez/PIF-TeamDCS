<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignación de Grupos a Campos Clínicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SECCIÓN 1: TABLA DE DISTRIBUCIONES (MAESTRO) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">1. Seleccione una Distribución (Cupos)</h3>
                </div>
                
                <div class="p-6">
                    {{-- 1. FORMULARIO DE FILTROS (FUERA DEL CONTENEDOR AJAX) --}}
                    {{-- Esto evita que se borre al escribir --}}
                    <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <form id="form-filtros" method="GET" action="{{ route('grupos.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                            
                            {{-- Barra de Búsqueda --}}
                            <div class="w-full md:flex-1">
                                <label for="input-search" class="block text-sm font-medium text-gray-700 mb-1">Buscar Distribución</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" 
                                            name="search" 
                                            id="input-search" 
                                            value="{{ request('search') }}"
                                            class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                            placeholder="Centro, Sede, Unidad..."
                                            autocomplete="off">
                                </div>
                            </div>

                            {{-- Filtro por Periodo --}}
                            <div class="w-full md:w-48">
                                <label for="select-periodo" class="block text-sm font-medium text-gray-700 mb-1">Periodo (Año)</label>
                                <select name="periodo" id="select-periodo" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos los años</option>
                                    @if(isset($periodosDisponibles))
                                        @foreach($periodosDisponibles as $year)
                                            <option value="{{ $year }}" {{ request('periodo') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Botón Limpiar --}}
                            <div class="flex space-x-2" id="btn-container">
                                <button type="button" 
                                        id="btn-limpiar"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
                                        title="Limpiar filtros">
                                    Limpiar
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. CONTENEDOR DE LA TABLA (AJAX TARGET) --}}
                    {{-- Solo esto se refrescará --}}
                    <div id="tabla-distribuciones-container" class="relative min-h-[200px]">
                        @include('grupos._tabla_distribuciones')
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: TABLA DE GRUPOS (DETALLE) --}}
            <div id="seccion-grupos" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-800">
                        2. Grupos Asignados
                        <span id="titulo-distribucion-seleccionada" class="text-sm font-normal text-blue-600 ml-2"></span>
                    </h3>
                    
                    @can('grupos.create')
                    <button id="btn-nuevo-grupo" data-distribucion-id="" class="bg-blue-600 hover:bg-blue-800 text-white text-sm font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-plus mr-2"></i> Agregar Grupo
                    </button>
                    @endcan
                </div>
                
                <div class="p-6">
                    <div id="tabla-grupos-container">
                        <p class="text-gray-500 text-center py-4">Cargando grupos...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE GRUPOS (CREAR/EDITAR) --}}
    <x-crud-modal modalId="grupoModal" formId="grupoForm" primaryKey="idGrupo" title="Grupo" enctype="multipart/form-data">
        <input type="hidden" id="idCupoDistribucion" name="idCupoDistribucion">

        <div class="mb-4">
            <label for="nombreGrupo" class="block text-sm font-medium text-gray-700">Nombre del Grupo *</label>
            <input type="text" id="nombreGrupo" name="nombreGrupo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required maxlength="45">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="fechaFin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" id="fechaFin" name="fechaFin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
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
                            {{ $dc->docente->nombresDocente ?? '' }} {{ $dc->docente->apellidoPaterno ?? '' }} 
                            ({{ $dc->sedeCarrera->sede->nombreSede ?? 'Sin Sede' }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </x-crud-modal>
    {{-- Modal para Ver Horario --}}
    <div id="verHorarioModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden flex items-center justify-center w-full h-full bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="relative w-full max-w-2xl max-h-full p-4">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Horario Detallado
                    </h3>
                    <button type="button" onclick="cerrarModalVerHorario()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6" id="verHorarioContent">
                    {{-- Contenido dinámico --}}
                </div>
                <!-- Modal footer -->
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
                content.innerHTML = '<p class="text-gray-500">No hay horarios definidos para esta oferta.</p>';
            } else {
                // Agrupar por hora de entrada y salida
                const grupos = {};
                
                horarios.forEach(h => {
                    const key = `${h.horaEntrada}-${h.horaSalida}`;
                    if (!grupos[key]) {
                        grupos[key] = {
                            entrada: h.horaEntrada,
                            salida: h.horaSalida,
                            dias: []
                        };
                    }
                    grupos[key].dias.push(h.diaSemana);
                });

                let html = '<div class="relative overflow-x-auto"><table class="w-full text-sm text-left text-gray-500"><thead class="text-xs text-gray-700 uppercase bg-gray-50"><tr><th scope="col" class="px-6 py-3">Días</th><th scope="col" class="px-6 py-3">Hora Inicio</th><th scope="col" class="px-6 py-3">Hora Fin</th></tr></thead><tbody>';
                
                Object.values(grupos).forEach(g => {
                    // Capitalizar días
                    const diasStr = g.dias.map(d => d.charAt(0).toUpperCase() + d.slice(1)).join(', ');
                    // Formatear horas (quitar segundos si es necesario, pero vienen como H:i:s)
                    const entrada = g.entrada ? g.entrada.substring(0, 5) : 'N/A';
                    const salida = g.salida ? g.salida.substring(0, 5) : 'N/A';

                    html += `<tr class="bg-white border-b">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${diasStr}</td>
                        <td class="px-6 py-4">${entrada}</td>
                        <td class="px-6 py-4">${salida}</td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                content.innerHTML = html;
            }
            
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        }

        function cerrarModalVerHorario() {
            const modal = document.getElementById('verHorarioModal');
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }
    </script>

    @vite(['resources/js/app.js']) 
</x-app-layout>