<div class="space-y-6">
    <!-- Header / Welcome -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Bienvenido, {{ Auth::user()->nombreUsuario }} {{ Auth::user()->apellidoPaterno }}</h3>
        <p class="text-sm text-gray-500">Panel de Gestión - Encargado de Campos Clínicos</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- KPI: Brecha de Cobertura -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 col-span-1 md:col-span-2 lg:col-span-1">
            <h4 class="text-lg font-semibold text-gray-800 mb-6 text-center">Brecha de Cobertura</h4>
            
            <div class="flex flex-col items-center justify-center">
                <!-- Gauge / Progress Circle -->
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <!-- Background Circle -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="10" />
                        <!-- Progress Circle -->
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#4f46e5" stroke-width="10" 
                                stroke-dasharray="283" 
                                stroke-dashoffset="{{ 283 - (283 * $kpiBrecha['porcentaje'] / 100) }}" 
                                stroke-linecap="round"
                                class="transition-all duration-1000 ease-out" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-4xl font-bold text-gray-900">{{ $kpiBrecha['porcentaje'] }}%</span>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">Cubierto</span>
                    </div>
                </div>
                
                <div class="mt-6 text-center space-y-2">
                    <p class="text-lg text-gray-700">
                        Llevamos un <span class="font-bold text-indigo-600">{{ $kpiBrecha['porcentaje'] }}%</span> de la demanda cubierta.
                    </p>
                    <p class="text-md text-gray-600">
                        Faltan asignar <span class="font-bold text-red-500">{{ $kpiBrecha['faltantes'] }}</span> cupos.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 w-full flex justify-center gap-8 text-sm">
                        <div class="text-center">
                            <span class="block font-bold text-gray-900">{{ $kpiBrecha['otorgados'] }}</span>
                            <span class="text-gray-500">Otorgados</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-gray-900">{{ $kpiBrecha['solicitados'] }}</span>
                            <span class="text-gray-500">Solicitados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI: Tasa de Ocupación -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 col-span-1">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Tasa de Ocupación de Campos</h4>
            
            <div class="flex items-end justify-between mb-4">
                <div>
                    <span class="text-5xl font-bold text-gray-900">{{ $kpiOcupacion['porcentaje'] }}%</span>
                    <span class="text-sm text-gray-500 ml-2">Utilización</span>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Oferta Total</div>
                    <div class="font-semibold text-gray-900">{{ $kpiOcupacion['total'] }}</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $kpiOcupacion['porcentaje'] }}%"></div>
            </div>

            <!-- Insight -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            @if($kpiOcupacion['porcentaje'] < 50)
                                ¡Atención! Baja ocupación. Podríamos estar desperdiciando convenios.
                            @elseif($kpiOcupacion['porcentaje'] < 85)
                                Ocupación moderada. Aún hay capacidad disponible.
                            @else
                                Alta ocupación. Estamos aprovechando bien los recursos.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Top 3 Centros Disponibles -->
            @if(isset($topCentrosDisponibles) && count($topCentrosDisponibles) > 0)
            <div class="mt-6 border-t pt-4">
                <h5 class="text-sm font-medium text-gray-700 mb-3">Centros con Mayor Disponibilidad</h5>
                <ul class="space-y-3">
                    @foreach($topCentrosDisponibles as $centro)
                    <li class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-gray-600 truncate max-w-[150px]" title="{{ $centro['nombre'] }}">{{ $centro['nombre'] }}</span>
                        </div>
                        <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ $centro['disponible'] }} cupos</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Sección Inferior: Gestión Legal y Alertas -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6 border-t-4 border-red-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Semáforo de Convenios Docente-Asistenciales</h3>
            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                Atención Requerida
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centro Formador</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Término</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Restantes</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conveniosAlertas as $convenio)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $convenio->centroFormador->nombreCentroFormador ?? 'Desconocido' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($convenio->fechaFin)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold {{ $convenio->dias_restantes < 0 ? 'text-red-600' : 'text-gray-700' }}">
                                {{ $convenio->dias_restantes }} días
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $convenio->color === 'red' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $convenio->color === 'orange' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $convenio->color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $convenio->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay convenios próximos a vencer en los siguientes 6 meses.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
