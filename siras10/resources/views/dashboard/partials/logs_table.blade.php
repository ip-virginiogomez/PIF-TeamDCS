<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entidad</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalles</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($logs ?? [] as $log)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs overflow-hidden">
                                @if($log->causer && $log->causer->foto)
                                    <img src="{{ asset('storage/' . $log->causer->foto) }}" alt="{{ $log->causer->nombreUsuario }}" class="h-full w-full object-cover">
                                @else
                                    {{ substr($log->causer ? ($log->causer->nombreUsuario ?? 'S') : 'S', 0, 2) }}
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $log->causer ? ($log->causer->nombreUsuario ?? 'Usuario #'.$log->causer_id) : 'Sistema' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $log->causer ? ($log->causer->correo ?? '') : '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $color = 'gray';
                            $text = 'Desconocido';
                            switch($log->event) {
                                case 'created': $color = 'green'; $text = 'Creado'; break;
                                case 'updated': $color = 'blue'; $text = 'Actualizado'; break;
                                case 'deleted': $color = 'red'; $text = 'Eliminado'; break;
                                default: $text = ucfirst($log->event);
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $text }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="font-medium">{{ class_basename($log->subject_type) }}</span>
                        <span class="text-xs text-gray-400 ml-1">#{{ $log->subject_id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="toggleDetails('{{ $log->id }}')" class="w-full px-4 py-2 rounded-md text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 focus:outline-none transition-colors duration-150 font-medium">
                            Ver cambios
                        </button>
                    </td>
                </tr>
                <tr id="details-{{ $log->id }}" class="hidden bg-gray-50">
                    <td colspan="5" class="px-6 py-4">
                        <div class="text-xs font-mono bg-gray-100 p-3 rounded border border-gray-200 overflow-x-auto">
                            @if(isset($log->properties['attributes']) || isset($log->properties['old']))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if(isset($log->properties['old']))
                                        <div class="{{ !isset($log->properties['attributes']) ? 'md:col-span-2' : '' }}">
                                            <strong class="text-red-600 block mb-1">
                                                {{ $log->event === 'deleted' ? 'Datos Eliminados:' : 'Antes:' }}
                                            </strong>
                                            <ul class="list-disc list-inside text-xs break-all">
                                                @foreach($log->properties['old'] as $key => $value)
                                                    <li><span class="font-semibold">{{ $key }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    @if(isset($log->properties['attributes']))
                                        <div class="{{ !isset($log->properties['old']) ? 'md:col-span-2' : '' }}">
                                            <strong class="text-green-600 block mb-1">
                                                {{ $log->event === 'created' ? 'Datos Creados:' : 'Después:' }}
                                            </strong>
                                            <ul class="list-disc list-inside text-xs break-all">
                                                @foreach($log->properties['attributes'] as $key => $value)
                                                    <li><span class="font-semibold">{{ $key }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <pre class="text-xs">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No hay registros de actividad recientes.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($logs instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    @endif
</div>
