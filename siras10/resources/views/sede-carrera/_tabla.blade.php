<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">Listado de Carreras Específicas</h3>
        <button data-modal-target="crudModal" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            Añadir Carrera
        </button>
    </div>
    
    <div id="tabla-container" data-sede-id="{{ $sede->idSede }}">
        @include('sede-carrera._tabla', ['carrerasEspecificas' => $carrerasEspecificas])
    </div>
</div>

{{-- Modal para Crear/Editar --}}
<div id="crudModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 id="modalTitle" class="text-2xl font-bold"></h3>
            <button data-action="close-modal" class="cursor-pointer z-50">
                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path></svg>
            </button>
        </div>
        <form id="crudForm" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="idSede" value="{{ $sede->idSede }}">
            <div>
                <label for="nombreSedeCarrera" class="block text-sm font-medium text-gray-700">Nombre de la Carrera</label>
                <input type="text" name="nombreSedeCarrera" id="nombreSedeCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <span id="error-nombreSedeCarrera" class="text-red-500 text-sm"></span>
            </div>
            <div>
                <label for="codigoCarrera" class="block text-sm font-medium text-gray-700">Código</label>
                <input type="text" name="codigoCarrera" id="codigoCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <span id="error-codigoCarrera" class="text-red-500 text-sm"></span>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700">
                    <span id="btnTexto"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de Carreras Específicas --}}
<div class="overflow-x-auto border border-gray-200 rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Carrera
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Código
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($carrerasEspecificas as $carrera)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- Se añade una comprobación para evitar errores con datos huérfanos --}}
                        <div class="text-sm font-medium text-gray-900">{{ $carrera->carrera->nombreCarrera ?? 'Carrera no encontrada' }}</div>
                        <div class="text-sm text-gray-500">{{ $carrera->nombreSedeCarrera }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $carrera->codigoCarrera }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                        <button data-action="edit" data-id="{{ $carrera->idSedeCarrera }}" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                        <button data-action="delete" data-id="{{ $carrera->idSedeCarrera }}" class="text-red-600 hover:text-red-900">Eliminar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                        No hay carreras para esta sede. Puede añadir una nueva.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>