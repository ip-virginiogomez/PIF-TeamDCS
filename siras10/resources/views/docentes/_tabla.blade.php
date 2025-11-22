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
                        $url = route('docentes.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('runDocente', 'RUN') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('foto', 'Foto') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('nombresDocente', 'Nombres') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('apellidoPaterno', 'Primer Apellido') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('apellidoMaterno', 'Segundo Apellido') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('fechaNacto', 'Fecha Nacimiento') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $link('correo', 'Correo Electrónico') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> Documentos </th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($docentes as $docente)
            <tr class="border-b" id="docente-{{ $docente->idDocente }}">
                <td class="py-2 px-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $docente->runDocente }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    @if($docente->foto)
                        <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/' . $docente->foto) }}" alt="Foto de {{ $docente->nombres }}">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <span class="text-xs">{{ substr($docente->nombresDocente, 0, 1) }}{{ substr($docente->apellidoPaterno, 0, 1) }}</span>
                        </div>
                    @endif
                </td>
                <td class="py-2 px-4" style="min-width: 150px;">
                    <span>{{ $docente->nombresDocente }}</span>
                </td>
                <td class="py-2 px-4" style="min-width: 120px;">
                    <span>{{ $docente->apellidoPaterno }}</span>
                </td>
                <td class="py-2 px-4" style="min-width: 120px;">
                    <span>{{ $docente->apellidoMaterno }}</span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $docente->fechaNacto ? \Carbon\Carbon::parse($docente->fechaNacto)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <span>{{ $docente->correo }}</span>
                </td>
                <td class="py-2 px-4 text-center">
                    <button data-action="view-documents" 
                            data-id="{{ $docente->runDocente }}"
                            title="Ver documentos"
                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2 items-center">
                        <button data-action="edit" data-id="{{ $docente->runDocente }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button data-action="delete" data-id="{{ $docente->runDocente }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-2"></i>
                        <span>No hay docentes registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($docentes, 'links'))
    <div class="mt-4">
        {{ $docentes->appends(request()->query())->links() }}
    </div>
@endif