<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Docentes') }}
            </h2>
            @can('docentes.create')
            <button data-modal-target="docenteModal" data-modal-toggle="docenteModal    " class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Docente
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                <div class="mb-6">
                        <form  id="search-form" action="{{ route('docentes.index') }}" method="GET" class="flex items-center gap-2">
                            <div class="relative w-full max-w-md">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    {{-- Icono Lupa --}}
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input
                                    id="search-input" 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Buscar por RUN, Nombre o Apellido..."
                                    autocomplete="off">
                                <button type="button" 
                                        id="btn-clear-search"
                                        class="hidden absolute inset-y-0 right-0 items-center pr-3 text-gray-400 hover:text-gray-600 cursor-pointer focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div id="tabla-container">
                        @include('docentes._tabla',[
                            'docentes' => $docentes,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="docenteModal" 
        formId="docenteForm" 
        primaryKey="runDocente"
        title="Nuevo Docente">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="mb-4">
            <label for="idSedeCarrera" class="block text-sm font-medium text-gray-700">Asignar a Sede/Carrera *</label>
            <select name="idSedeCarrera" id="idSedeCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                <option value="">Seleccione una opción...</option>
                
                @if(isset($sedesCarreras))
                    @foreach($sedesCarreras as $sede)
                        <option value="{{ $sede->idSedeCarrera }}">
                            {{ $sede->nombreSedeCarrera }} ({{ $sede->sede->nombreSede ?? 'Sin Sede' }})
                        </option>
                    @endforeach
                @endif
            </select>
            <div id="error-idSedeCarrera" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
        
        <div class="mb-4">
            <label for="runDocente" class="block text-sm font-medium text-gray-700">RUN *</label>
            <input type="text" 
                id="runDocente" 
                name="runDocente" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="Ej: 12345678-9"
                required>
            <div id="run-help-text" class="text-xs text-amber-600 mt-1 hidden">
                El RUN no puede modificarse al editar un docente existente
            </div>
            <div id="error-runDocente" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="nombresDocente" class="block text-sm font-medium text-gray-700">Nombres *</label>
            <input type="text" 
                id="nombresDocente" 
                name="nombresDocente" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
            <div id="error-nombresDocente" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Primer Apellido</label>
            <input type="text" 
                id="apellidoPaterno" 
                name="apellidoPaterno" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
            <div id="error-apellidoPaterno" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Segundo Apellido</label>
            <input type="text" 
                id="apellidoMaterno" 
                name="apellidoMaterno" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div id="error-apellidoMaterno" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="fechaNacto" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
            <input type="date" 
                id="fechaNacto" 
                name="fechaNacto" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
            <div id="error-fechaNacto" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="profesion" class="block text-sm font-medium text-gray-700">Profesión *</label>
            <input type="text" 
                id="profesion" 
                name="profesion" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
            <div id="error-profesion" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
    </div>
    <div class="mb-4">
        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
        <input type="email" 
            id="correo" 
            name="correo" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            required>
        <div id="error-correo" class="text-red-500 text-sm mt-1 hidden"></div>
    </div>

    <hr class="my-6 border-t border-gray-200">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <div class="mb-4">
            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
            <input type="file" 
                id="foto" 
                name="foto" 
                accept="image/*"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div id="error-foto" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="curriculum" class="block text-sm font-medium text-gray-700">Curriculum Vitae</label>
            <div id="curriculum-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="curriculum-link" href="#" target="_blank" class="underline">Ver curriculum</a>
            </div>
            <input type="file" 
                id="curriculum" 
                name="curriculum" 
                accept=".pdf,.doc,.docx"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div id="error-curriculum" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="certSuperInt" class="block text-sm font-medium text-gray-700">Certificado Superintendencia</label>
            <div id="certSuperInt-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="certSuperInt-link" href="#" target="_blank" class="underline">Ver certificado</a>
            </div>
            <input type="file" 
                id="certSuperInt" 
                name="certSuperInt" 
                accept=".pdf,.doc,.docx"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div id="error-certSuperInt" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="certRCP" class="block text-sm font-medium text-gray-700">Certificado RCP</label>
            <div id="certRCP-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="certRCP-link" href="#" target="_blank" class="underline">Ver certificado</a>
            </div>
            <input type="file" 
                id="certRCP" 
                name="certRCP" 
                accept=".pdf,.doc,.docx"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div id="error-certRCP" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="certIAAS" class="block text-sm font-medium text-gray-700">Certificado IAAS</label>
            <div id="certIAAS-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="certIAAS-link" href="#" target="_blank" class="underline">Ver certificado</a>
            </div>
            <input type="file" 
                id="certIAAS" 
                name="certIAAS" 
                accept=".pdf,.doc,.docx"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div id="error-certIAAS" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label for="acuerdo" class="block text-sm font-medium text-gray-700">Documento de Acuerdo</label>
            <div id="acuerdo-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="acuerdo-link" href="#" target="_blank" class="underline">Ver documento</a>
            </div>
            <input type="file" 
                id="acuerdo" 
                name="acuerdo" 
                accept=".pdf,.doc,.docx"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div id="error-acuerdo" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
    </div>

    </x-crud-modal>
    <x-info-modal modalId="documentosModal" title="Documentos del Docente" maxWidth="3xl" />
    @vite(['resources/js/app.js'])
</x-app-layout>