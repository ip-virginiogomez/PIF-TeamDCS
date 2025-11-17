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
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $role->id }}
                        </span>
                    </td>
                    <td class="py-2 px-4">{{ $role->name }}</td>
                    <td class="py-2 px-4 flex space-x-2">
                        <button data-action="edit" data-id="{{ $role->id }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        
                        @if ($role->name != 'Admin')
                            <button data-action="delete" data-id="{{ $role->id }}" 
                                class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-8">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-user-tag text-6xl mb-4"></i>
                            <p class="text-lg">No hay roles registrados</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $roles->appends(request()->query())->links() }}
</div>
