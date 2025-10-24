<div class="space-y-4 p-2">
    @php
        $documentos = [
            ['nombre' => 'Curriculum Vitae', 'campo' => $docente->curriculum],
            ['nombre' => 'Certificado Superintendencia', 'campo' => $docente->certSuperInt],
            ['nombre' => 'Certificado RCP', 'campo' => $docente->certRCP],
            ['nombre' => 'Certificado IAAS', 'campo' => $docente->certIAAS],
            ['nombre' => 'Documento de Acuerdo', 'campo' => $docente->acuerdo],
        ];
        
        $hasDocs = false;
    @endphp

    @foreach ($documentos as $doc)
        @if($doc['campo'])
            @php $hasDocs = true; @endphp
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-200">
                <span class="text-sm font-medium text-gray-700">
                    <i class="fas fa-file-alt fa-fw mr-2 text-gray-500"></i>
                    {{ $doc['nombre'] }}
                </span>
                <a href="{{ asset('storage/' . $doc['campo']) }}" target="_blank" 
                class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-semibold px-3 py-1 rounded-md hover:bg-blue-100">
                    Ver Documento
                </a>
            </div>
        @endif
    @endforeach

    @if(!$hasDocs)
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-box-open fa-3x mb-3 text-gray-400"></i>
            <p class="font-medium">No hay documentos adjuntos</p>
            <p class="text-sm">Este docente aún no ha subido ningún archivo.</p>
        </div>
    @endif
</div>