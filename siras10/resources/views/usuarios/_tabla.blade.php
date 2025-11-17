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
                        $url = route('usuarios.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left"> {!! $link('runUsuario', 'RUN') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombreUsuario', 'Nombre') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('correo', 'Correo') !!}</th>
                <th class="py-2 px-4 text-left">Teléfono</th>
                <th class="py-2 px-4 text-left">Roles</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $usuario)
            <tr class="border-b" id="usuario-{{ $usuario->runUsuario }}">
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $usuario->runUsuario }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $usuario->nombreUsuario }} {{ $usuario->apellidoPaterno }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4 text-sm">{{ $usuario->correo }}</td>
                <td class="py-2 px-4">
                    @if($usuario->telefono)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $usuario->telefono }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm">Sin teléfono</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    @foreach ($usuario->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-1">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td class="py-2 px-4 flex space-x-2">
                    <button data-action="edit" data-id="{{ $usuario->runUsuario }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $usuario->runUsuario }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                        <span>No hay usuarios registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($usuarios, 'links'))
    <div class="mt-4">
        {{ $usuarios->appends(request()->query())->links() }}
    </div>
@endif
