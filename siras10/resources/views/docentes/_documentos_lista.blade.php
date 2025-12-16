{{-- CONTENEDOR 1: LISTA DE DOCUMENTOS --}}
<div id="lista-documentos-container" class="space-y-4 p-2 transition-opacity duration-300">
    @php
        $documentos = [
            ['key' => 'curriculum', 'nombre' => 'Curriculum Vitae', 'campo' => $docente->curriculum],
            ['key' => 'certSuperInt', 'nombre' => 'Certificado Superintendencia', 'campo' => $docente->certSuperInt],
            ['key' => 'certRCP', 'nombre' => 'Certificado RCP', 'campo' => $docente->certRCP],
            ['key' => 'certIAAS', 'nombre' => 'Certificado IAAS', 'campo' => $docente->certIAAS],
            ['key' => 'acuerdo', 'nombre' => 'Documento de Acuerdo', 'campo' => $docente->acuerdo],
        ];

        foreach($docente->docenteVacunas as $vacuna) {
            if($vacuna->documento && $vacuna->estadoVacuna && $vacuna->estadoVacuna->nombreEstado === 'Activo') {
            $documentos[] = [
                'key' => 'vacuna_' . $vacuna->idDocenteVacuna,
                'nombre' => $vacuna->tipoVacuna->nombreVacuna ?? 'Vacuna',
                'campo' => $vacuna->documento
            ];
            }
        }

        $hasDocs = false;
    @endphp

    @foreach ($documentos as $doc)
        @if($doc['campo'])
            @php 
                $hasDocs = true; 
                $urlArchivo = asset('storage/' . $doc['campo']);
                $docKey = $doc['key'];
            @endphp
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-200 hover:bg-gray-100 transition">
                {{-- Nombre del Archivo --}}
                <div class="flex items-center overflow-hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 truncate" title="{{ $doc['nombre'] }}">
                        {{ $doc['nombre'] }}
                    </span>
                </div>

                <div class="flex items-center space-x-2 ml-2">
                    
                    <button type="button"
                        data-action="preview-doc" 
                        data-url="{{ $urlArchivo }}"
                        data-title="{{ $doc['nombre'] }}"
                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition duration-200 focus:outline-none"
                        title="Previsualizar documento">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>

                    <a href="{{ $urlArchivo }}" download 
                        class="p-2 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-full transition duration-200"
                        title="Descargar documento">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                    @if(!isset($readonly) || !$readonly)
                    <button type="button" 
                            data-action="change-doc" 
                            data-doc-key="{{ $docKey }}"
                            data-docente-id="{{ $docente->runDocente }}"
                            class="text-yellow-600 hover:text-yellow-800 p-2 rounded-full hover:bg-yellow-100 transition duration-150 ease-in-out"
                            title="Cambiar archivo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form id="form-change-{{ $docKey }}-{{ $docente->runDocente }}" class="hidden" enctype="multipart/form-data">
                        @csrf
                        <input type="file" 
                            name="{{ $docKey }}" {{-- El nombre del input es la clave del documento --}}
                            data-doc-key="{{ $docKey }}"
                            data-docente-id="{{ $docente->runDocente }}"
                            accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            class="hidden" 
                            onchange="docenteManager.handleFileChange(this)"> 
                    </form>
                    @endif
                </div>
            </div>
        @endif
    @endforeach

    @if(!$hasDocs)
        <div class="text-center text-gray-500 py-8 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
            </svg>
            <p class="font-medium text-lg text-gray-600">No hay documentos</p>
            <p class="text-sm mb-4 text-gray-400">Este docente aún no tiene archivos.</p>

            @if(!isset($readonly) || !$readonly)
            <button type="button" data-action="edit" data-id="{{ $docente->runDocente }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150 shadow-md text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Subir Documentos
            </button>
            @endif
        </div>
    @endif
</div>

{{-- CONTENEDOR 2: IFRAME DE PREVISUALIZACIÓN (Oculto por defecto) --}}
<div id="preview-documento-container" class="hidden flex-col h-full p-2">
    <div class="flex items-center justify-between mb-3 border-b pb-2">
        <button type="button" id="btn-cerrar-preview" class="flex items-center text-gray-600 hover:text-blue-600 transition font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a la lista
        </button>
        <span id="preview-titulo" class="text-sm font-semibold text-gray-700 truncate max-w-xs"></span>
    </div>

    {{-- Iframe --}}
    <div class="flex-grow bg-gray-100 rounded-lg overflow-hidden border border-gray-300 relative">
        <iframe id="doc-viewer-iframe" src="" class="w-full h-[75vh]" frameborder="0"></iframe>
    </div>
</div>