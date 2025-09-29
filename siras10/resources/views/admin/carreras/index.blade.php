<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Carreras') }}
            </h2>
            @can('carreras.create')
                <a href="{{ route('carreras.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Crear Nueva Carrera
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border-green-400 text-green-700 border-l-4 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">Nombre</th>
                                    <th class="py-2 px-4 text-left">Fecha de Creación</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carreras as $carrera)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $carrera->nombreCarrera }}</td>
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($carrera->fechaCreacion)->format('d-m-Y') }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            @can('carreras.update')
                                                <a href="{{ route('carreras.edit', $carrera) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            @endcan
                                            @can('carreras.delete')
                                                <form action="{{ route('carreras.destroy', $carrera) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta carrera?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center">No hay carreras registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $carreras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>