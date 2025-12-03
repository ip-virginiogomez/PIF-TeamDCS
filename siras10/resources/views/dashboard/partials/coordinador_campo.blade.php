<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-sky-700">Dashboard: Coordinador de Campo Clínico</h3>
        <p class="text-sm text-gray-500">
            @if(isset($periodoActual))
                Periodo: {{ $periodoActual->Año }} ({{ \Carbon\Carbon::parse($periodoActual->fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodoActual->fechaFin)->format('d/m/Y') }})
            @else
                Sin periodo activo
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
        <!-- Semana de Rotación -->
        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Semana de Rotación</div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ isset($semanaRotacion) && $semanaRotacion > 0 ? $semanaRotacion : '-' }}
                    </div>
                </div>
                <div class="p-2 bg-blue-100 rounded-full text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alumnos Totales (Centro Formador) -->
        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Alumnos Totales</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $alumnosTotalesCF ?? 0 }}</div>
                </div>
                <div class="p-2 bg-indigo-100 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alumnos en Práctica -->
        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">En Práctica</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $alumnosEnPractica ?? 0 }}</div>
                </div>
                <div class="p-2 bg-green-100 rounded-full text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alumnos Finalizados -->
        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Práctica Finalizada</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $alumnosFinalizados ?? 0 }}</div>
                </div>
                <div class="p-2 bg-gray-100 rounded-full text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alumnos Pendientes -->
        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Pendientes</div>
                    <div class="text-xs text-gray-400">(Por realizar práctica)</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $alumnosPendientes ?? 0 }}</div>
                </div>
                <div class="p-2 bg-yellow-100 rounded-full text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget: Rotaciones Activas Hoy -->
    <div class="bg-white shadow rounded-lg p-6 mb-6 border-l-4 border-teal-500">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Rotaciones Activas Actuales</h3>
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="p-3 bg-teal-100 rounded-full text-teal-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $rotacionesActivas['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Grupos en práctica</div>
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Desglose por Centro de Salud:</h4>
                <div class="bg-gray-50 rounded-lg p-3 max-h-40 overflow-y-auto">
                    @if(isset($rotacionesActivas['desglose']) && count($rotacionesActivas['desglose']) > 0)
                        <ul class="space-y-2">
                            @foreach($rotacionesActivas['desglose'] as $centro => $cantidad)
                                <li class="flex justify-between text-sm border-b border-gray-200 pb-1 last:border-0 last:pb-0">
                                    <span class="text-gray-700 truncate mr-2" title="{{ $centro }}">{{ $centro }}</span>
                                    <span class="font-semibold text-gray-900 whitespace-nowrap">{{ $cantidad }} grupos</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 italic">No hay rotaciones activas hoy.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Widget: Ocupación de Centros de Salud -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Ocupación de Centros de Salud (Actual)</h3>
        <div class="relative h-64">
            <canvas id="ocupacionChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Widget: Estado de Inmunización Global (Alumnos) -->
        <div class="bg-white shadow rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Estado de Inmunización Alumnos</h4>
            <div class="relative h-64">
                <canvas id="inmunizacionChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Haga clic en la sección "Vencidas" para ver detalles.</p>
            </div>
        </div>

        <!-- Widget: Estado de Inmunización Docentes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Estado de Inmunización Docentes</h4>
            <div class="relative h-64">
                <canvas id="inmunizacionDocenteChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Haga clic en la sección "Vencidas" para ver detalles.</p>
            </div>
        </div>
    </div>

    <!-- Widget: Vacunas por Vencer (Próximos 30 días) -->
    <div class="bg-white shadow rounded-lg p-6 mb-6 border-l-4 border-red-500 mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Vacunas por Vencer (Próximos 30 días)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacuna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vence el</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Restantes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vacunasPorVencer ?? [] as $vacuna)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $vacuna->alumno->nombres }} {{ $vacuna->alumno->apellidoPaterno }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $vacuna->tipoVacuna->nombreVacuna }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $vacuna->fechaVencimiento->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold">
                                {{ now()->diffInDays($vacuna->fechaVencimiento, false) }} días
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                No hay vacunas próximas a vencer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
