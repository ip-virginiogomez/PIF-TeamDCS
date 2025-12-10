@forelse($demandas as $demanda)
<div onclick="seleccionarDemanda(this, {{ $demanda->idDemandaCupo }}, '{{ $demanda->idTipoPractica }}', '{{ $demanda->sedeCarrera->idCarrera }}', {{ $demanda->pendiente }})" 
    class="card-demanda cursor-pointer bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition group focus:ring-2 focus:ring-blue-500 relative mb-3"
    tabindex="0">
    
    <div class="flex justify-between items-start">
        <div>
            <p class="font-bold text-gray-800">{{ $demanda->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? '' }} - {{ $demanda->sedeCarrera->sede->nombreSede }}</p>
            <p class="text-sm text-gray-600 font-semibold">{{ $demanda->sedeCarrera->carrera->nombreCarrera }}</p>
            <p class="text-sm text-gray-500">{{ $demanda->asignatura }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $demanda->nombreTipoPractica ?? 'Práctica General' }}</p>
        </div>
        <div class="text-right">
            <span class="block text-2xl font-bold text-red-600">{{ $demanda->pendiente }}</span>
            <span class="text-xs text-gray-400">de {{ $demanda->cuposSolicitados }}</span>
        </div>
    </div>
    <div class="absolute inset-0 border-2 border-blue-500 rounded-lg opacity-0 pointer-events-none transition-opacity duration-200 selection-ring"></div>
</div>
@empty
<div class="text-center py-10 text-gray-400">
    <p>No hay demandas pendientes que coincidan con la búsqueda.</p>
</div>
@endforelse

<div class="mt-4">
    {{ $demandas->links() }}
</div>
