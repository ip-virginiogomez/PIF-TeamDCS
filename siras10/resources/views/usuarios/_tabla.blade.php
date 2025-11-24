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
                    <span>{{ $usuario->nombreUsuario }} {{ $usuario->apellidoPaterno }}</span>
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
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        <button data-action="edit" data-id="{{ $usuario->runUsuario }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button data-action="delete" data-id="{{ $usuario->runUsuario }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
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
