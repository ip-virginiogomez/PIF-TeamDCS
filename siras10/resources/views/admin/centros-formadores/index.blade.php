<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Centros Formadores') }}
            </h2>
            <a href="{{ route('centros-formadores.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo
            </a>
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
                                    <th class="py-2 px-4 text-left">ID</th>
                                    <th class="py-2 px-4 text-left">Nombre</th>
                                    <th class="py-2 px-4 text-left">Tipo de Centro</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($centros as $centro)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $centro->idCentroFormador }}</td>
                                        <td class="py-2 px-4">{{ $centro->nombreCentroFormador }}</td>
                                        <td class="py-2 px-4">{{ $centro->tipoCentroFormador->nombreTipo }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <a href="{{ route('centros-formadores.edit', $centro) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            <form action="{{ route('centros-formadores.destroy', $centro) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este elemento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center">No hay centros formadores registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $centros->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>