<div class="space-y-6">
    <!-- User Management Section -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Gestión de Usuarios</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Users -->
                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">Total Usuarios</p>
                            <p class="text-2xl font-bold text-indigo-900">{{ $userStats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Assignments -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-green-600">Asignaciones</p>
                        <div class="p-2 bg-green-100 rounded-full">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Centros Formadores:</span>
                            <span class="font-semibold text-gray-900">{{ $userStats['assigned_cf'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Centros de Salud:</span>
                            <span class="font-semibold text-gray-900">{{ $userStats['assigned_cs'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Chart -->
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex flex-col items-center justify-center row-span-2">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">Actividad (30 días)</h4>
                    <div class="relative h-32 w-32">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 text-center mb-2">
                        <div class="flex items-center justify-center gap-2">
                            <span class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Activos</span>
                            <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> Inactivos</span>
                        </div>
                    </div>
                    <button onclick="openActivityModal()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium focus:outline-none underline">
                        Ver Detalle
                    </button>
                </div>

                <!-- Roles Breakdown -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 md:col-span-2">
                    <p class="text-sm font-medium text-gray-600 mb-3">Usuarios por Rol</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($userStats['by_role'] as $role)
                            <div class="bg-white p-2 rounded border border-gray-100 flex justify-between items-center">
                                <span class="text-xs text-gray-500 truncate mr-2" title="{{ $role->name }}">{{ $role->name }}</span>
                                <span class="text-xs font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full">{{ $role->users_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Storage Section -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Almacenamiento del Sistema</h3>
        </div>
        <div class="p-6">
            <!-- Folders Breakdown -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Vacunas -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Vacunas</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['vacunas'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Acuerdos -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Acuerdos</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['acuerdos'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fotos -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Fotos</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['fotos'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Docentes -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Docentes</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['docentes'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Convenios -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 011.414.586l4.414 4.414a1 1 0 01.586 1.414V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Convenios</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['convenios'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mallas -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Mallas</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['mallas'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Programas -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-pink-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Programas</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['programas'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pautas -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-teal-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Pautas</h4>
                            <p class="text-lg font-semibold text-gray-700">{{ $storage['pautas'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Logs Section -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-medium text-gray-900">Registro de Actividad Reciente</h3>
            <div class="w-full sm:w-auto">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="logs-search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Buscar por usuario, acción, entidad...">
                </div>
            </div>
        </div>
        <div id="logs-container">
            @include('dashboard.partials.logs_table')
        </div>
    </div>
</div>

<!-- Activity Details Modal -->
<div id="activityModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeActivityModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Detalle de Actividad de Usuarios</h3>
                        <div class="mt-4">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button onclick="switchTab('active')" id="tab-active" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm w-1/2 focus:outline-none">
                                        Usuarios Activos ({{ $userStats['activity']['active'] }})
                                    </button>
                                    <button onclick="switchTab('inactive')" id="tab-inactive" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm w-1/2 focus:outline-none">
                                        Usuarios Inactivos ({{ $userStats['activity']['inactive'] }})
                                    </button>
                                </nav>
                            </div>
                            <div class="mt-4 h-64 overflow-y-auto">
                                <div id="content-active" class="block">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($userStats['activity']['details']['active'] as $u)
                                            <li class="py-2 flex justify-between items-center">
                                                <div class="text-sm font-medium text-gray-900">{{ $u->nombreUsuario }} {{ $u->apellidoPaterno }} {{ $u->apellidoMaterno }}</div>
                                                <div class="text-xs text-gray-500">{{ $u->runUsuario }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div id="content-inactive" class="hidden">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($userStats['activity']['details']['inactive'] as $u)
                                            <li class="py-2 flex justify-between items-center">
                                                <div class="text-sm font-medium text-gray-900">{{ $u->nombreUsuario }} {{ $u->apellidoPaterno }} {{ $u->apellidoMaterno }}</div>
                                                <div class="text-xs text-gray-500">{{ $u->runUsuario }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeActivityModal()">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // AJAX Pagination and Search for Logs
        const logsContainer = document.getElementById('logs-container');
        const searchInput = document.getElementById('logs-search');
        let searchTimeout;

        function fetchLogs(url) {
            logsContainer.style.opacity = '0.5';
            
            const urlObj = new URL(url);
            if (searchInput && searchInput.value) {
                urlObj.searchParams.set('search', searchInput.value);
            }
            
            fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                logsContainer.innerHTML = html;
                logsContainer.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error loading logs:', error);
                logsContainer.style.opacity = '1';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Reset to page 1 when searching
                    const url = new URL(window.location.origin + window.location.pathname);
                    url.searchParams.set('logs_page', 1);
                    fetchLogs(url.toString());
                }, 500);
            });
        }

        if (logsContainer) {
            logsContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a') || e.target.closest('nav[role="navigation"] a');
                if (link) {
                    e.preventDefault();
                    fetchLogs(link.href);
                }
            });
        }

        // User Activity Chart
        const ctx = document.getElementById('userActivityChart');
        if (ctx) {
            const activeUsers = {{ $userStats['activity']['active'] }};
            const inactiveUsers = {{ $userStats['activity']['inactive'] }};

            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Inactivos'],
                    datasets: [{
                        data: [activeUsers, inactiveUsers],
                        backgroundColor: [
                            '#10B981', // Green-500
                            '#E5E7EB'  // Gray-200
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    let value = context.raw;
                                    let total = context.chart._metasets[context.datasetIndex].total;
                                    let percentage = Math.round((value / total) * 100) + '%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    },
                    cutout: '75%'
                }
            });
        }
    });

    function toggleDetails(id) {
        const el = document.getElementById('details-' + id);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }

    function openActivityModal() {
        document.getElementById('activityModal').classList.remove('hidden');
    }

    function closeActivityModal() {
        document.getElementById('activityModal').classList.add('hidden');
    }

    function switchTab(tab) {
        const activeTab = document.getElementById('tab-active');
        const inactiveTab = document.getElementById('tab-inactive');
        const activeContent = document.getElementById('content-active');
        const inactiveContent = document.getElementById('content-inactive');

        if (tab === 'active') {
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            inactiveTab.classList.remove('border-indigo-500', 'text-indigo-600');
            inactiveTab.classList.add('border-transparent', 'text-gray-500');
            
            activeContent.classList.remove('hidden');
            inactiveContent.classList.add('hidden');
        } else {
            inactiveTab.classList.add('border-indigo-500', 'text-indigo-600');
            inactiveTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.remove('border-indigo-500', 'text-indigo-600');
            activeTab.classList.add('border-transparent', 'text-gray-500');

            inactiveContent.classList.remove('hidden');
            activeContent.classList.add('hidden');
        }
    }

    function confirmRestore(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se intentará restaurar el registro a su estado anterior.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restore-form-' + id).submit();
            }
        });
    }
</script>
