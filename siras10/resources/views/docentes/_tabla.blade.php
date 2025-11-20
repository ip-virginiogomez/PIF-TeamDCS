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
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"">
                        <div>
                            <span class="font-medium">{{ $docente->runDocente }}</span>
                        </div>
                    </div>
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
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <span class="font-medium">{{ $docente->nombresDocente }}</span>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <span class="font-medium">{{ $docente->apellidoPaterno }}</span>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <span class="font-medium">{{ $docente->apellidoMaterno }}</span>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $docente->fechaNacto ? \Carbon\Carbon::parse($docente->fechaNacto)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <div>
                        <div>
                            <span class="font-medium">{{ $docente->correo }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4 text-center">
                    <button data-action="view-documents" 
                            data-id="{{ $docente->runDocente }}"
                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-800 bg-white hover:bg-blue-50 rounded-full transition-colors duration-200 shadow-sm"
                            title="Ver documentos">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </td>
                <td class="py-2 px-4 flex space-x-2 items-center">
                    <button data-action="edit" data-id="{{ $docente->runDocente }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $docente->runDocente }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
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