<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Estudiantes') }}
            </h2>
            <a href="{{ route('alumnos.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Estudiante
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">Nombre Completo</th>
                                    <th class="py-2 px-4 text-left">RUN</th>
                                    <th class="py-2 px-4 text-left">Correo</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alumnos as $alumno)
                                    <tr class="border-b">
                                        <td class="py-2 px-4 flex items-center space-x-3">
                                            @if($alumno->foto)
                                                <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <span class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">?</span>
                                            @endif
                                            <span>{{ $alumno->nombres }} {{ $alumno->apellidoPaterno }}</span>
                                        </td>
                                        <td class="py-2 px-4">{{ $alumno->runAlumno }}</td>
                                        <td class="py-2 px-4">{{ $alumno->correo }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            <a href="{{ route('alumnos.edit', $alumno) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a>
                                            <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center">No hay estudiantes registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $alumnos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>