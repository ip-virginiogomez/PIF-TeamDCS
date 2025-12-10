@forelse($ofertas as $oferta)
<div class="card-oferta bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-green-500 transition mb-3"
    data-tipo-practica="{{ $oferta->idTipoPractica }}"
    data-carrera="{{ $oferta->idCarrera }}"
    data-id="{{ $oferta->idCupoOferta }}"
    data-centro="{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'Centro' }}"
    data-unidad="{{ $oferta->unidadClinica->nombreUnidad ?? 'Unidad' }}"
    data-disponible="{{ $oferta->disponible }}">
    
    <div class="flex justify-between items-center">
        <div class="flex items-center flex-1">
            <div class="bg-green-100 text-green-700 font-bold p-2 rounded mr-3 h-10 w-10 flex items-center justify-center">
                {{ substr($oferta->unidadClinica->centroSalud->nombreCentro ?? 'C', 0, 2) }}
            </div>
            <div>
                <h4 class="font-bold text-gray-800">{{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'Centro Desconocido' }}</h4>
                <p class="text-sm text-gray-600">{{ $oferta->unidadClinica->nombreUnidad }}</p>
                <p class="text-xs text-gray-400">{{ $oferta->tipoPractica->nombrePractica }} - {{ $oferta->carrera->nombreCarrera }}</p>
            </div>
        </div>
        
        <div class="text-right pl-4">
            <span class="text-xl font-bold text-green-600">{{ $oferta->disponible }}</span>
            <span class="text-xs block text-gray-400">libres</span>
            
            <button onclick="abrirModalAsignacion({{ $oferta->idCupoOferta }}, '{{ $oferta->unidadClinica->centroSalud->nombreCentro }}', '{{ $oferta->unidadClinica->nombreUnidad }}', {{ $oferta->disponible }})"
                    class="mt-2 bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700 transition shadow-sm">
                Asignar
            </button>
        </div>
    </div>
</div>
@empty
<div class="text-center py-20 text-gray-400 flex flex-col items-center justify-center h-full">
    @if(isset($waitingSelection) && $waitingSelection)
        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <p class="text-lg">Selecciona una demanda a la izquierda</p>
        <p class="text-sm">para ver los centros de salud compatibles.</p>
    @else
        <svg class="w-16 h-16 mb-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <p class="text-lg text-gray-600">No hay ofertas compatibles</p>
        <p class="text-sm">No se encontraron cupos disponibles para esta práctica y carrera.</p>
    @endif
</div>
@endforelse

@if($ofertas->count() > 0)
<div class="mt-4">
    {{ $ofertas->links() }}
</div>
@endif
