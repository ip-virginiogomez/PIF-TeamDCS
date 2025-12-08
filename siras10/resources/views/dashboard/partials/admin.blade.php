<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Alumnos -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Alumnos</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $totalAlumnos }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Docentes -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Docentes</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $totalDocentes }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cupos -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Cupos</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $totalCupos }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Usuarios</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $totalUsuarios }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centros Formadores -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Centros Formadores</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $totalCentrosFormadores }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Cupos por Carrera</h3>
            <div class="relative h-64">
                <canvas id="cuposChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 flex items-center justify-center">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bienvenido al Panel de Administración</h3>
                <p class="text-gray-500">Seleccione una opción del menú para comenzar a gestionar el sistema.</p>
            </div>
        </div>
    </div>

    <!-- Activity Logs Section -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Registro de Actividad Reciente</h3>
        </div>
        <div id="logs-container">
            @include('dashboard.partials.logs_table')
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cuposChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.cuposPorCarreraLabels,
                datasets: [{
                    label: 'Cupos Ofertados',
                    data: window.cuposPorCarreraData,
                    backgroundColor: 'rgba(99, 102, 241, 0.5)', // Indigo-500 with opacity
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // AJAX Pagination for Logs
        const logsContainer = document.getElementById('logs-container');
        if (logsContainer) {
            logsContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a') || e.target.closest('nav[role="navigation"] a');
                if (link) {
                    e.preventDefault();
                    const url = link.getAttribute('href');
                    
                    // Add loading state opacity
                    logsContainer.style.opacity = '0.5';
                    
                    fetch(url, {
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
</script>
