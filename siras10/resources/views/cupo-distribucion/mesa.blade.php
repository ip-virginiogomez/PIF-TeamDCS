<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Distribución de Cupos') }}
        </h2>
    </x-slot>

    <div class="h-[calc(100vh-65px)] flex flex-col overflow-y-auto">
        {{-- Header Info --}}
        <div class="bg-white border-b p-4 shadow-sm z-10 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <label for="periodo_selector" class="text-sm font-medium text-gray-700">Periodo:</label>
                <select id="periodo_selector" onchange="cambiarPeriodo(this.value)" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-1 pl-2 pr-8">
                    @foreach($periodos as $periodo)
                        <option value="{{ $periodo->idPeriodo }}" {{ $periodo->idPeriodo == $periodoActual->idPeriodo ? 'selected' : '' }}>
                            {{ $periodo->Año }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Contenedor Principal --}}
        <div class="flex-none h-[60vh] min-h-[500px] flex bg-gray-100 border-b border-gray-300">
            
            {{-- COLUMNA IZQUIERDA: DEMANDA (Quién pide) --}}
            <div class="w-1/2 p-4 overflow-y-auto border-r border-gray-300 custom-scrollbar">
                <div class="sticky top-0 bg-gray-100 z-10 pb-2">
                    <h3 class="font-bold mb-3 text-red-600 flex items-center">
                        <span class="bg-red-100 p-1 rounded mr-2"></span> Demandas Pendientes
                    </h3>
                    <div class="relative">
                        <input type="text" 
                                id="search-demanda" 
                                placeholder="Buscar por centro, carrera, práctica..." 
                                class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onkeyup="filtrarDemandas()">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3" id="lista-demandas">
                    @include('cupo-distribucion._lista_demandas')
                </div>
            </div>

            {{-- COLUMNA DERECHA: OFERTA (Quién tiene) --}}
            <div class="w-1/2 p-4 overflow-y-auto bg-gray-50 custom-scrollbar">
                <h3 class="font-bold mb-3 text-green-600 flex items-center sticky top-0 bg-gray-50 py-2 z-10">
                    <span class="bg-green-100 p-1 rounded mr-2"></span> Oferta Disponible
                </h3>

                <div id="lista-ofertas" class="space-y-3">
                    @include('cupo-distribucion._lista_ofertas', ['waitingSelection' => true])
                </div>
            </div>
        </div>

        {{-- Tabla de Distribuciones --}}
        <div class="p-4 bg-gray-50">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Cupos Distribuidos</h3>
                <div class="relative w-64">
                    <input type="text" 
                        id="search-distribucion" 
                        placeholder="Buscar..." 
                        class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onkeyup="filtrarDistribuciones()">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div id="lista-distribuciones">
                @include('cupo-distribucion._lista_distribuciones')
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

    {{-- MODAL DE HORARIOS --}}
    <div id="modalHorario" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Horario Detallado</h3>
                <button onclick="cerrarModalHorario()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salida</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-horarios-body" class="bg-white divide-y divide-gray-200">
                            <!-- Contenido dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="cerrarModalHorario()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        let demandaSeleccionadaId = null;
        let demandaPendienteQty = 0;
        let currentTipoPractica = null;
        let currentCarrera = null;

        // Inicializar paginación AJAX
        document.addEventListener('DOMContentLoaded', function() {
            initPagination('lista-demandas');
            initPagination('lista-ofertas');
            initPagination('lista-distribuciones');
        });

        function initPagination(containerId) {
            const container = document.getElementById(containerId);
            container.addEventListener('click', function(e) {
                // Detectar enlaces de paginación (Laravel usa nav > a o .pagination > a)
                const link = e.target.closest('nav[role="navigation"] a') || e.target.closest('.pagination a');
                
                if (link) {
                    e.preventDefault();
                    const url = link.href;
                    
                    // Añadir parámetros extra si es necesario
                    const finalUrl = new URL(url);
                    if (containerId === 'lista-demandas') {
                        finalUrl.searchParams.set('type', 'demandas');
                        const search = document.getElementById('search-demanda').value;
                        if (search) finalUrl.searchParams.set('search', search);
                    } else if (containerId === 'lista-ofertas') {
                        finalUrl.searchParams.set('type', 'ofertas');
                        if (currentTipoPractica) finalUrl.searchParams.set('tipo_practica_id', currentTipoPractica);
                        if (currentCarrera) finalUrl.searchParams.set('carrera_id', currentCarrera);
                    } else if (containerId === 'lista-distribuciones') {
                        finalUrl.searchParams.set('type', 'distribuciones');
                        const search = document.getElementById('search-distribucion').value;
                        if (search) finalUrl.searchParams.set('search', search);
                    }

                    fetch(finalUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.text())
                        .then(html => {
                            container.innerHTML = html;
                        });
                }
            });
        }

        function cambiarPeriodo(periodoId) {
            const url = new URL(window.location.href);
            url.searchParams.set('periodo_id', periodoId);
            // Resetear paginación al cambiar de periodo
            url.searchParams.delete('page'); 
            url.searchParams.delete('dist_page');
            window.location.href = url.toString();
        }

        function seleccionarDemanda(element, id, tipoPracticaId, carreraId, pendiente) {
            demandaSeleccionadaId = id;
            demandaPendienteQty = pendiente;
            currentTipoPractica = tipoPracticaId;
            currentCarrera = carreraId;

            // 1. Visual: Resaltar la tarjeta seleccionada
            document.querySelectorAll('.card-demanda').forEach(el => {
                el.classList.remove('bg-blue-50', 'border-blue-500');
                el.classList.add('border-red-500'); // Restaurar borde rojo
                el.querySelector('.selection-ring').classList.remove('opacity-100');
            });
            
            element.classList.remove('border-red-500');
            element.classList.add('bg-blue-50', 'border-blue-500');
            element.querySelector('.selection-ring').classList.add('opacity-100');

            // 2. Cargar Ofertas AJAX
            cargarOfertas(tipoPracticaId, carreraId);
        }

        function cargarOfertas(tipoPracticaId, carreraId) {
            const container = document.getElementById('lista-ofertas');
            container.innerHTML = '<div class="text-center py-10"><svg class="animate-spin h-8 w-8 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';

            const url = new URL(window.location.href);
            url.searchParams.set('type', 'ofertas');
            url.searchParams.set('tipo_practica_id', tipoPracticaId);
            url.searchParams.set('carrera_id', carreraId);
            url.searchParams.set('page', 1); // Reset page

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                });
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
                    if (response.status === 422) {
                        let errorMessages = '';
                        if (data.errors) {
                            // Concatenar todos los mensajes de error
                            errorMessages = Object.values(data.errors).flat().join('\n');
                        } else {
                            errorMessages = data.message || 'Error de validación.';
                        }
                        throw new Error(errorMessages);
                    }
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
                    // Recargar listas manteniendo estado si es posible, o reload completo
                    // Para simplificar, recargamos la página, pero idealmente recargaríamos solo las listas
                    window.location.reload();
                });

            } catch (error) {
                Swal.fire({
                    title: 'Error',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                });
            }
        }

        let searchTimeout;
        function filtrarDemandas() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const input = document.getElementById('search-demanda');
                const search = input.value;
                const container = document.getElementById('lista-demandas');
                
                container.innerHTML = '<div class="text-center py-4 text-gray-500">Buscando...</div>';

                const url = new URL(window.location.href);
                url.searchParams.set('type', 'demandas');
                url.searchParams.set('search', search);
                url.searchParams.set('page', 1);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                    });
            }, 300); // Debounce 300ms
        }

        let searchDistribucionTimeout;
        function filtrarDistribuciones() {
            clearTimeout(searchDistribucionTimeout);
            searchDistribucionTimeout = setTimeout(() => {
                const input = document.getElementById('search-distribucion');
                const search = input.value;
                const container = document.getElementById('lista-distribuciones');
                
                // container.innerHTML = '<div class="text-center py-4 text-gray-500">Buscando...</div>';

                const url = new URL(window.location.href);
                url.searchParams.set('type', 'distribuciones');
                url.searchParams.set('search', search);
                url.searchParams.set('dist_page', 1);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                    });
            }, 300);
        }

        function verHorario(horarios) {
            const modal = document.getElementById('modalHorario');
            const tbody = document.getElementById('tabla-horarios-body');
            tbody.innerHTML = '';

            if (horarios && horarios.length > 0) {
                horarios.forEach(h => {
                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${h.diaSemana}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${h.horaEntrada}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${h.horaSalida}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No hay horarios definidos</td></tr>';
            }

            modal.classList.remove('hidden');
        }

        function cerrarModalHorario() {
            document.getElementById('modalHorario').classList.add('hidden');
        }

        // Event delegation para el botón de eliminar
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action="delete-distribucion"]');
            if (!btn) return;

            const id = btn.dataset.id;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la asignación de cupos y devolverá la cantidad a la oferta original.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarDistribucion(id);
                }
            });
        });

        async function eliminarDistribucion(id) {
            try {
                const response = await fetch(`/cupo-distribuciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al eliminar');
                }

                Swal.fire(
                    '¡Eliminado!',
                    data.message || 'El registro ha sido eliminado.',
                    'success'
                ).then(() => {
                    window.location.reload();
                });

            } catch (error) {
                Swal.fire(
                    'Error',
                    error.message,
                    'error'
                );
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
