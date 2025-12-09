<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Distribución de Cupos') }}
        </h2>
    </x-slot>

    <div class="h-[calc(100vh-65px)] flex flex-col">
        {{-- Header Info --}}
        <div class="bg-white border-b p-4 shadow-sm z-10 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Periodo Actual: <strong>{{ $periodoActual->Año ?? 'N/A' }}</strong></p>
            </div>
        </div>

        {{-- Contenedor Principal --}}
        <div class="flex-1 flex overflow-hidden bg-gray-100">
            
            {{-- COLUMNA IZQUIERDA: DEMANDA (Quién pide) --}}
            <div class="w-1/2 p-4 overflow-y-auto border-r border-gray-300 custom-scrollbar">
                <h3 class="font-bold mb-3 text-red-600 flex items-center sticky top-0 bg-gray-100 py-2 z-10">
                    <span class="bg-red-100 p-1 rounded mr-2"></span> Demandas Pendientes
                </h3>
                
                <div class="space-y-3">
                    @forelse($demandas as $demanda)
                    <div onclick="seleccionarDemanda(this, {{ $demanda->idDemandaCupo }}, '{{ $demanda->idTipoPractica }}', '{{ $demanda->sedeCarrera->idCarrera }}', {{ $demanda->pendiente }})" 
                        class="card-demanda cursor-pointer bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition group focus:ring-2 focus:ring-blue-500 relative"
                        tabindex="0">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $demanda->nombreTipoPractica ?? 'Práctica General' }}</h4>
                                <p class="text-sm text-gray-600 font-semibold">{{ $demanda->sedeCarrera->carrera->nombreCarrera }}</p>
                                <p class="text-sm text-gray-500">{{ $demanda->sedeCarrera->sede->nombreSede }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $demanda->asignatura }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-2xl font-bold text-red-600">{{ $demanda->pendiente }}</span>
                                <span class="text-xs text-gray-400">de {{ $demanda->cuposSolicitados }}</span>
                            </div>
                        </div>
                        <div class="absolute inset-0 border-2 border-blue-500 rounded-lg opacity-0 pointer-events-none transition-opacity duration-200 selection-ring"></div>
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-400">
                        <p>No hay demandas pendientes para este periodo.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- COLUMNA DERECHA: OFERTA (Quién tiene) --}}
            <div class="w-1/2 p-4 overflow-y-auto bg-gray-50 custom-scrollbar">
                <h3 class="font-bold mb-3 text-green-600 flex items-center sticky top-0 bg-gray-50 py-2 z-10">
                    <span class="bg-green-100 p-1 rounded mr-2"></span> Oferta Disponible
                </h3>

                <div id="lista-ofertas" class="space-y-3">
                    @foreach($ofertas as $oferta)
                    {{-- Data attributes para filtrar con JS --}}
                    <div class="card-oferta hidden bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-green-500 transition"
                         data-tipo-practica="{{ $oferta->idTipoPractica }}"
                         data-carrera="{{ $oferta->idCarrera }}"
                         data-id="{{ $oferta->idCupoOferta }}"
                         data-centro="{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'Centro' }}"
                         data-unidad="{{ $oferta->unidadClinica->nombreUnidad ?? 'Unidad' }}"
                         data-disponible="{{ $oferta->disponible }}">
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center flex-1">
                                <div class="bg-green-100 text-green-700 font-bold p-2 rounded mr-3 h-10 w-10 flex items-center justify-center">
                                    {{ substr($oferta->unidadClinica->centroSalud->nombreCentro ?? 'C', 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'Centro Desconocido' }}</h4>
                                    <p class="text-sm text-gray-600">{{ $oferta->unidadClinica->nombreUnidad }}</p>
                                    <p class="text-xs text-gray-400">{{ $oferta->tipoPractica->nombrePractica }} - {{ $oferta->carrera->nombreCarrera }}</p>
                                </div>
                            </div>
                            
                            <div class="text-right pl-4">
                                <span class="text-xl font-bold text-green-600">{{ $oferta->disponible }}</span>
                                <span class="text-xs block text-gray-400">libres</span>
                                
                                <button onclick="abrirModalAsignacion({{ $oferta->idCupoOferta }}, '{{ $oferta->unidadClinica->centroSalud->nombreCentro }}', '{{ $oferta->unidadClinica->nombreUnidad }}', {{ $oferta->disponible }})"
                                        class="mt-2 bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700 transition shadow-sm">
                                    Asignar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div id="empty-state-oferta" class="text-center py-20 text-gray-400 flex flex-col items-center justify-center h-full">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <p class="text-lg">Selecciona una demanda a la izquierda</p>
                        <p class="text-sm">para ver los centros de salud compatibles.</p>
                    </div>
                    
                    <div id="no-match-state" class="hidden text-center py-20 text-gray-400 flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 mb-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-lg text-gray-600">No hay ofertas compatibles</p>
                        <p class="text-sm">No se encontraron cupos disponibles para esta práctica y carrera.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE ASIGNACIÓN --}}
    <div id="modalAsignacion" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Confirmar Asignación</h3>
                <button onclick="cerrarModalAsignacion()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="formAsignacion" onsubmit="guardarAsignacion(event)">
                @csrf
                <div class="p-6 space-y-4">
                    <input type="hidden" id="input_demanda_id" name="idDemandaCupo">
                    <input type="hidden" id="input_oferta_id" name="idCupoOferta">
                    
                    <div class="bg-blue-50 p-3 rounded-md border border-blue-100">
                        <p class="text-sm text-blue-800 font-semibold mb-1">Asignando a:</p>
                        <p id="lbl_centro" class="text-lg font-bold text-gray-800">Centro</p>
                        <p id="lbl_unidad" class="text-sm text-gray-600">Unidad</p>
                    </div>

                    <div>
                        <label for="input_cantidad" class="block text-sm font-medium text-gray-700">Cantidad de Cupos</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="cantCupos" id="input_cantidad" required min="1" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0">
                        </div>
                        <p id="lbl_max" class="mt-1 text-xs text-gray-500">Máximo posible: 0</p>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar Asignación
                    </button>
                    <button type="button" onclick="cerrarModalAsignacion()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let demandaSeleccionadaId = null;
        let demandaPendienteQty = 0;

        function seleccionarDemanda(element, id, tipoPracticaId, carreraId, pendiente) {
            demandaSeleccionadaId = id;
            demandaPendienteQty = pendiente;

            // 1. Visual: Resaltar la tarjeta seleccionada
            document.querySelectorAll('.card-demanda').forEach(el => {
                el.classList.remove('bg-blue-50', 'border-blue-500');
                el.classList.add('border-red-500'); // Restaurar borde rojo
                el.querySelector('.selection-ring').classList.remove('opacity-100');
            });
            
            element.classList.remove('border-red-500');
            element.classList.add('bg-blue-50', 'border-blue-500');
            element.querySelector('.selection-ring').classList.add('opacity-100');

            // 2. Filtrar Ofertas (Match por Tipo Práctica y Carrera)
            const ofertas = document.querySelectorAll('.card-oferta');
            let encontradas = 0;

            ofertas.forEach(card => {
                const ofertaTipo = card.dataset.tipoPractica;
                const ofertaCarrera = card.dataset.carrera;

                // Comparación laxa (==) por si vienen como string/number
                if (ofertaTipo == tipoPracticaId && ofertaCarrera == carreraId) {
                    card.classList.remove('hidden');
                    encontradas++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // 3. Manejar estado vacío
            const emptyState = document.getElementById('empty-state-oferta');
            const noMatchState = document.getElementById('no-match-state');
            
            emptyState.classList.add('hidden');
            
            if (encontradas === 0) {
                noMatchState.classList.remove('hidden');
            } else {
                noMatchState.classList.add('hidden');
            }
        }

        function abrirModalAsignacion(ofertaId, nombreCentro, nombreUnidad, disponibles) {
            const modal = document.getElementById('modalAsignacion');
            modal.classList.remove('hidden');

            // Llenar inputs del modal
            document.getElementById('input_demanda_id').value = demandaSeleccionadaId;
            document.getElementById('input_oferta_id').value = ofertaId;
            
            // Sugerir cantidad: El menor valor entre lo que falta y lo que hay
            const sugerido = Math.min(demandaPendienteQty, disponibles);
            const inputCantidad = document.getElementById('input_cantidad');
            
            inputCantidad.value = sugerido;
            inputCantidad.max = sugerido;
            inputCantidad.focus();
            
            // Textos informativos
            document.getElementById('lbl_centro').innerText = nombreCentro;
            document.getElementById('lbl_unidad').innerText = nombreUnidad;
            document.getElementById('lbl_max').innerText = `Máximo posible: ${sugerido}`;
        }

        function cerrarModalAsignacion() {
            document.getElementById('modalAsignacion').classList.add('hidden');
        }

        async function guardarAsignacion(e) {
            e.preventDefault();
            
            const form = document.getElementById('formAsignacion');
            const formData = new FormData(form);
            
            try {
                const response = await fetch('{{ route("cupo-distribuciones.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al guardar');
                }

                // Éxito
                cerrarModalAsignacion();
                
                Swal.fire({
                    title: '¡Asignación Exitosa!',
                    text: data.message || 'Los cupos han sido asignados correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.reload();
                });

            } catch (error) {
                alert(error.message);
            }
        }
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8; 
        }
    </style>
</x-app-layout>
