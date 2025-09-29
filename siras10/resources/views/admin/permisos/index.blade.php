<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Permisos del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Información</p>
                        <p>Esta es una lista de todos los permisos generados automáticamente por el sistema. Se gestionan a través del archivo <strong>MenuAndPermissionsSeeder</strong>.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">ID</th>
                                    <th class="py-2 px-4 text-left">Nombre del Permiso (Name)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permisos as $permiso)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $permiso->id }}</td>
                                        <td class="py-2 px-4 font-mono">{{ $permiso->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 px-4 text-center">No hay permisos registrados. Ejecuta el Seeder.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $permisos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>