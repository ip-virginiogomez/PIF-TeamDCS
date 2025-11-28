<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left whitespace-nowrap">Año</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Fecha de Subida</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Archivo</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programas as $programa)
            <tr class="border-b">
                <td class="py-2 px-4 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($programa->fechaSubida)->format('Y') }}
                </td>
                <td class="py-2 px-4 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($programa->fechaSubida)->format('d/m/Y') }}
                </td>
                <td class="py-2 px-4 whitespace-nowrap">
                    <a href="{{ Storage::url($programa->documento) }}" target="_blank" class="text-blue-600 hover:underline">Ver PDF</a>
                </td>
                <td class="py-2 px-4 whitespace-nowrap">
                    <a href="{{ route('sede-carrera.programas.download', ['programa' => $programa->idPrograma]) }}" class="text-green-600 hover:underline mr-2">Descargar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-file-pdf text-4xl text-gray-300 mb-2"></i>
                        <span>No hay programas registrados para esta asignatura.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
