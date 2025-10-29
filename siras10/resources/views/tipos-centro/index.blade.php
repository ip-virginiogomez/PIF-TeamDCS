<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tipos de Centro Formador') }}
            </h2>
            @can('tipos-centro-formador.create')
                <a href="{{ route('tipos-centro-formador.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crear Nuevo
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="w-1/4 py-2 px-4 text-left">ID</th>
                                    <th class="w-1/2 py-2 px-4 text-left">Nombre</th>
                                    <th class="w-1/4 py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tipos as $tipo)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $tipo->idTipoCentroFormador }}</td>
                                        <td class="py-2 px-4">{{ $tipo->nombreTipo }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            @can('tipos-centro-formador.edit')
                                                <a href="{{ route('tipos-centro-formador.edit', $tipo) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            @endcan
                                            @can('tipos-centro-formador.delete')
                                                <form action="{{ route('tipos-centro-formador.destroy', $tipo) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este elemento?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center">No hay tipos de centro registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tipos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/app.js'])
</x-app-layout>