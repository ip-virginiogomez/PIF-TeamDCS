<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Roles') }}
            </h2>
            <a href="{{ route('roles.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Rol
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
                                    <th class="py-2 px-4 text-left">ID</th>
                                    <th class="py-2 px-4 text-left">Nombre del Rol</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $role->id }}</td>
                                        <td class="py-2 px-4">{{ $role->name }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <a href="{{ route('roles.edit', $role) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            
                                            @if ($role->name != 'Admin')
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center">No hay roles registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>