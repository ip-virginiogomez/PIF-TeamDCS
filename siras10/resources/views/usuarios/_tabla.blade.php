<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortIcon = function($column) use ($sortBy, $sortDirection) {
                        if ($sortBy !== $column) {
                            return '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        }
                        return $sortDirection === 'asc' 
                            ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                            : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                    };
                @endphp
                <th class="py-2 px-4 text-left cursor-pointer hover:bg-gray-100" onclick="toggleSort('runUsuario')">
                    <div class="flex items-center gap-1">
                        RUN {!! $getSortIcon('runUsuario') !!}
                    </div>
                </th>
                <th class="py-2 px-4 text-left">Foto</th>
                <th class="py-2 px-4 text-left cursor-pointer hover:bg-gray-100" onclick="toggleSort('nombreUsuario')">
                    <div class="flex items-center gap-1">
                        Nombre {!! $getSortIcon('nombreUsuario') !!}
                    </div>
                </th>
                <th class="py-2 px-4 text-left cursor-pointer hover:bg-gray-100" onclick="toggleSort('correo')">
                    <div class="flex items-center gap-1">
                        Correo {!! $getSortIcon('correo') !!}
                    </div>
                </th>
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
                    @if($usuario->foto)
                        <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/' . $usuario->foto) }}" alt="Foto de {{ $usuario->nombreUsuario }}">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <span class="text-xs">{{ substr($usuario->nombreUsuario, 0, 1) }}{{ substr($usuario->apellidoPaterno, 0, 1) }}</span>
                        </div>
                    @endif
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
                        @can('usuarios.update')
                        <button data-action="edit" data-id="{{ $usuario->runUsuario }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        @can('usuarios.delete')
                        <button data-action="delete" data-id="{{ $usuario->runUsuario }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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
                <td colspan="7" class="py-4 px-4 text-center text-gray-500">
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
