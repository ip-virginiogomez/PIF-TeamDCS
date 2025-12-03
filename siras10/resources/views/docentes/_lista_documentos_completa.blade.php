@php
    $documentosDocente = [];
    if($docente->curriculum) {
        $documentosDocente[] = [
            'nombre' => 'Currículum',
            'file' => $docente->curriculum
        ];
    }
    if($docente->certSuperInt) {
        $documentosDocente[] = [
            'nombre' => 'Certificado Superintendencia',
            'file' => $docente->certSuperInt
        ];
    }
    if($docente->certRCP) {
        $documentosDocente[] = [
            'nombre' => 'Certificado RCP',
            'file' => $docente->certRCP
        ];
    }
    if($docente->certIAAS) {
        $documentosDocente[] = [
            'nombre' => 'Certificado IAAS',
            'file' => $docente->certIAAS
        ];
    }
    if($docente->acuerdo) {
        $documentosDocente[] = [
            'nombre' => 'Acuerdo de Confidencialidad',
            'file' => $docente->acuerdo
        ];
    }
    foreach($docente->docenteVacunas as $vacuna) {
        if($vacuna->documento && $vacuna->estadoVacuna && $vacuna->estadoVacuna->nombreEstado === 'Activo') {
            $documentosDocente[] = [
                'nombre' => $vacuna->tipoVacuna->nombreVacuna ?? 'Vacuna',
                'file' => $vacuna->documento
            ];
        }
    }
@endphp

{{-- COLUMNA IZQUIERDA: Lista de Documentos --}}
<div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col">
    <div id="lista-docs-panel" class="flex-1 overflow-y-auto p-4 space-y-2">
        @forelse($documentosDocente as $doc)
            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 transition mb-2 hover:bg-gray-50 cursor-pointer" 
                onclick="document.getElementById('doc-iframe').src='{{ asset('storage/'.$doc['file']) }}'; document.getElementById('doc-iframe').classList.remove('hidden'); document.getElementById('empty-state').classList.add('hidden');">
                <div class="flex items-center overflow-hidden">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    <span class="text-sm font-medium text-gray-700 truncate" title="{{ $doc['nombre'] }}">{{ $doc['nombre'] }}</span>
                </div>
                {{-- Botón oculto para compatibilidad si JS lo busca --}}
                <button type="button" data-action="preview-doc" data-url="{{ asset('storage/'.$doc['file']) }}" class="hidden"></button>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">
                No hay documentos disponibles.
            </div>
        @endforelse
    </div>
    
    {{-- Botón Volver (Footer de la lista) --}}
    <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
        <button id="btn-back-docs" data-action="close-modal-docs" class="w-full inline-flex justify-center items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none transition-colors">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </button>
    </div>
</div>

{{-- COLUMNA DERECHA: Visor (Iframe) --}}
<div class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
    {{-- Estado Vacío --}}
    <div id="empty-state" class="text-center text-gray-400">
        <svg class="w-16 h-16 mb-4 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        <p>Selecciona un documento para visualizar</p>
    </div>
    
    {{-- Iframe --}}
    <iframe id="doc-iframe" src="" class="hidden w-full h-full rounded-lg border shadow-sm bg-white"></iframe>
</div>