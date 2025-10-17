<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-75 items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh]">
        
        <div class="flex items-center justify-between p-4 border-b flex-shrink-0">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">
                {{ $title }}
            </h3>
            <button type="button" data-action="close-modal" class="text-gray-400 hover:text-gray-600">
                <span class="sr-only">Cerrar modal</span>
                <svg class="h-6 w-6" xmlns="http://www.w.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form id="{{ $formId }}">
                @csrf
                {{ $slot }}
            </form>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t flex-shrink-0">
            <button 
                id="btnTexto"
                type="submit" 
                form="{{ $formId }}" 
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                {{ $buttonText ?? 'Guardar' }}
            </button>
            <button 
                type="button" 
                data-action="close-modal" 
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                Cancelar
            </button>
        </div>
    </div>
</div>