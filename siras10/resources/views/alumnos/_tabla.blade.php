<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $link = function ($columna, $texto) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $columna && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $symbol = '';
                        if ($sortBy == $columna) {
                            $symbol = $sortDirection == 'asc' ? '↑' : '↓';
                        }
                        $url = route('alumnos.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('runAlumno', 'RUN') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Foto</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('nombres', 'Nombres') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('apellidoPaterno', 'Primer Apellido') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('apellidoMaterno', 'Segundo Apellido') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('fechaNacto', 'Fecha Nacimiento') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('correo', 'Correo Electrónico') !!}</th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Documentos</th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alumnos as $alumno)
            <tr class="border-b" id="alumno-{{ $alumno->runAlumno }}">
                <td class="py-2 px-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $alumno->runAlumno }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    @if($alumno->foto)
                        <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <span class="text-xs">{{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidoPaterno, 0, 1) }}</span>
                        </div>
                    @endif
                </td>
                <td class="py-2 px-4" style="min-width: 150px;">
                    <span>{{ $alumno->nombres }}</span>
                </td>
                <td class="py-2 px-4" style="min-width: 120px;">
                    <span>{{ $alumno->apellidoPaterno }}</span>
                </td>
                <td class="py-2 px-4" style="min-width: 120px;">
                    <span>{{ $alumno->apellidoMaterno }}</span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $alumno->fechaNacto ? \Carbon\Carbon::parse($alumno->fechaNacto)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4 text-sm">{{ $alumno->correo }}</td>
                <td class="py-2 px-4 text-center">
                    <button data-action="view-documents" 
                            data-id="{{ $alumno->runAlumno }}"
                            data-nombre="{{ $alumno->nombres }} {{ $alumno->apellidoPaterno }}"
                            title="Ver documentos"
                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        
                        {{-- BOTÓN GESTIONAR VACUNAS --}}
                        <button type="button" 
                                data-action="manage-vacunas"
                                data-run="{{ $alumno->runAlumno }}"
                                data-nombre="{{ $alumno->nombres }} {{ $alumno->apellidoPaterno }}"
                                class="inline-flex items-center justify-center w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors shadow-sm" 
                                title="Gestionar Vacunas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </button>

                        {{-- Botón Editar --}}
                        @can('alumnos.update')
                        <button data-action="edit" data-id="{{ $alumno->runAlumno }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        
                        {{-- Botón Eliminar --}}
                        @can('alumnos.delete')
                        <button data-action="delete" data-id="{{ $alumno->runAlumno }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-user-graduate text-4xl text-gray-300 mb-2"></i>
                        <span>No hay alumnos registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($alumnos, 'links'))
    <div class="mt-4">
        {{ $alumnos->appends(request()->query())->links() }}
    </div>
@endif