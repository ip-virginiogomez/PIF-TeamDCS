<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <a href="{{ route('usuarios.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">RUN</th>
                                    <th class="py-2 px-4 text-left">Nombre</th>
                                    <th class="py-2 px-4 text-left">Correo</th>
                                    <th class="py-2 px-4 text-left">Roles</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usuarios as $usuario)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $usuario->runUsuario }}</td>
                                        <td class="py-2 px-4">{{ $usuario->nombres }} {{ $usuario->apellidoPaterno }}</td>
                                        <td class="py-2 px-4">{{ $usuario->correo }}</td>
                                        <td class="py-2 px-4">
                                            @foreach ($usuario->roles as $role)
                                                <span class="bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <a href="{{ route('usuarios.edit', $usuario) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center">No hay usuarios registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $usuarios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>