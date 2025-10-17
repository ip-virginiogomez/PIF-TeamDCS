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
                <th class="py-2 px-4 text-left"> {!! $link('idAlumno', 'RUN') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('foto', 'Foto') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombres', 'Nombres') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('apellidoPaterno', 'Apellido Paterno') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('apellidoMaterno', 'Apellido Materno') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('fechaNacto', 'Fecha Nacimiento') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('correo', 'Correo Electrónico') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alumnos as $alumno)
            <tr class="border-b" id="alumno-{{ $alumno->idAlumno }}">
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $alumno->runAlumno }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto del Alumno"> 
                        </div>
                    </div>
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
                <td class="py-2 px-4 flex space-x-2">
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
                <td colspan="7" class="py-4 px-4 text-center text-gray-500">
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
                        