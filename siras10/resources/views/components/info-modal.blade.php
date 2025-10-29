@props(['modalId', 'title'])

<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-75 items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg flex flex-col max-h-[90vh]">
        
        <div class="flex items-center justify-between p-4 border-b flex-shrink-0">
            <h3 class="text-lg font-medium text-gray-900" id="{{ $modalId }}-title">
                {{ $title }}
            </h3>
            <button type="button" data-action="close-info-modal" data-modal-id="{{ $modalId }}" class="text-gray-400 hover:text-gray-600">
                <span class="sr-only">Cerrar modal</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto" id="{{ $modalId }}-body">
            <div class="flex justify-center items-center h-32">
                <i class="fas fa-spinner fa-spin fa-2x text-gray-500"></i>
            </div>
        </div>
        
    </div>
</div>