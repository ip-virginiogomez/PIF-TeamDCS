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
                <th class="py-2 px-4 text-left"> {!! $link('runAlumno', 'RUN') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('foto', 'Foto') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombres', 'Nombres') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('apellidoPaterno', 'Apellido Paterno') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('apellidoMaterno', 'Apellido Materno') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('fechaNacto', 'Fecha Nacimiento') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('correo', 'Correo Electrónico') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('acuerdo', 'Acuerdo') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alumnos as $alumno)
            <tr class="border-b" id="alumno-{{ $alumno->idAlumno }}">
                <td class="py-2 px-4">
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"">
                        <div>
                            <span class="font-medium">{{ $alumno->runAlumno }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    @if($alumno->foto)
                        <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto de {{ $alumno->nombres }}">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <span class="text-xs">{{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidoPaterno, 0, 1) }}</span>
                        </div>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->nombres }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->apellidoPaterno }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->apellidoMaterno }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->fechaNacto ? \Carbon\Carbon::parse($alumno->fechaNacto)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->correo }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    @if($alumno->acuerdo)
                        <a href="{{ asset('storage/' . $alumno->acuerdo) }}" 
                            target="_blank" 
                            class="text-blue-600 hover:text-blue-800 hover:underline"
                            title="Ver documento">
                            <i class="fas fa-file-pdf fa-lg">Ver Documento</i>
                    @else
                        <span class="text-gray-500 text-center">N/A</span>
                    @endif
                </td>
                <td class="py-2 px-4 flex space-x-2 items-center">
                    <button data-action="edit" data-id="{{ $alumno->runAlumno }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $alumno->runAlumno }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-building text-4xl text-gray-300 mb-2"></i>
                        <span>No hay alumnos registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($alumnos, 'links'))
        <div class="mt-4">
            {{ $alumnos->appends(request()->query())->links() }}
        </div>
    @endif
</div>
                        