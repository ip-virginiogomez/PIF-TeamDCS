<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Docentes') }}
            </h2>
        @can('docentes.create')
            <button onclick="limpiarFormularioDocente()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Docente
            </button>
        @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="py-2 px-4 text-left">Nombre Completo</th>
                                    <th class="py-2 px-4 text-left">RUN</th>
                                    <th class="py-2 px-4 text-left">Correo</th>
                                    <th class="py-2 px-4 text-left">Profesion</th>
                                    <th class="py-2 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($docentes as $docente)
                                    <tr id="docente-{{ $docente->runDocente }}" class="border-b">
                                        <td class="py-2 px-4 flex items-center space-x-3">
                                            @if($docente->foto)
                                                <img src="{{ asset('storage/' . $docente->foto) }}" alt="Foto" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <span class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">?</span>
                                            @endif
                                            <span>{{ $docente->nombresDocente }} {{ $docente->apellidoPaterno }}</span>
                                        </td>
                                        <td class="py-2 px-4">{{ $docente->runDocente }}</td>
                                        <td class="py-2 px-4">{{ $docente->correo }}</td>
                                        <td class="py-2 px-4">{{ $docente->profesion }}</td>
                                        <td class="py-2 px-4 flex space-x-2">
                                            @can('docentes.update')
                                                <button onclick="editarDocente('{{ $docente->runDocente }}')" class="text-yellow-500 hover:text-yellow-700">Editar</button>
                                            @endcan
                                            @can('docentes.delete')
                                                <button onclick="eliminarDocente('{{ $docente->runDocente }}')" class="text-red-500 hover:text-red-700">Eliminar</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center">No hay docentes registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $docentes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar docentes -->
    <x-crud-modal 
        modalId="docenteModal" 
        formId="docenteForm" 
        primaryKey="runDocente"
        title="Nuevo Docente"
        buttonText="Guardar Docente"
        closeFunction="cerrarModalDocente()">
        
        <form id="docenteForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="method" value="POST">
            <input type="hidden" name="runDocente" id="runDocente">
            
            <!-- Campo RUN visible (con formato) -->
            <div class="mb-4">
                <label for="runDocenteVisible" class="block text-sm font-medium text-gray-700">RUN *</label>
                <input type="text" 
                       id="runDocenteVisible" 
                       name="runDocente" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       placeholder="Ej: 12.345.678-9"
                       required>
                <div id="run-help-text" class="text-xs text-amber-600 mt-1 hidden">
                    El RUN no puede modificarse al editar un docente existente
                </div>
                <div id="error-runDocente" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Nombres -->
            <div class="mb-4">
                <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres *</label>
                <input type="text" 
                       id="nombresDocente" 
                       name="nombresDocente" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       required>
                <div id="error-nombres" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Apellido Paterno -->
            <div class="mb-4">
                <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
                <input type="text" 
                       id="apellidoPaterno" 
                       name="apellidoPaterno" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       required>
                <div id="error-apellidoPaterno" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Apellido Materno -->
            <div class="mb-4">
                <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                <input type="text" 
                       id="apellidoMaterno" 
                       name="apellidoMaterno" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <div id="error-apellidoMaterno" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Correo -->
            <div class="mb-4">
                <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                <input type="email" 
                       id="correo" 
                       name="correo" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       required>
                <div id="error-correo" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div class="mb-4">
                <label for="fechaNacto" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
                <input type="date" 
                       id="fechaNacto" 
                       name="fechaNacto" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       required>
                <div id="error-fechaNacto" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Profesión -->
            <div class="mb-4">
                <label for="profesion" class="block text-sm font-medium text-gray-700">Profesión *</label>
                <input type="text" 
                       id="profesion" 
                       name="profesion" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       required>
                <div id="error-profesion" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Documentos y Certificados -->
            <div class="border-t mt-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos y Certificados</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto del Docente</label>
                        @if(isset($docente) && $docente->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $docente->foto) }}" alt="Foto actual" class="h-20 w-20 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" 
                               id="foto" 
                               name="foto" 
                               accept="image/*"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG (máx. 2MB)</div>
                        <div id="error-foto" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Curriculum -->
                    <div class="mb-4">
                        <label for="curriculum" class="block text-sm font-medium text-gray-700">Curriculum Vitae</label>
                        @if(isset($docente) && $docente->curriculum)
                            <div class="mb-2 text-sm text-blue-600">
                                <a href="{{ asset('storage/' . $docente->curriculum) }}" target="_blank" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Ver curriculum actual
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="curriculum" 
                               name="curriculum" 
                               accept=".pdf,.doc,.docx"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
                        <div id="error-curriculum" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Certificado Superintendencia -->
                    <div class="mb-4">
                        <label for="certSuperInt" class="block text-sm font-medium text-gray-700">Certificado Superintendencia</label>
                        @if(isset($docente) && $docente->certSuperInt)
                            <div class="mb-2 text-sm text-blue-600">
                                <a href="{{ asset('storage/' . $docente->certSuperInt) }}" target="_blank" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Ver certificado actual
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="certSuperInt" 
                               name="certSuperInt" 
                               accept=".pdf,.doc,.docx"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
                        <div id="error-certSuperInt" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Certificado RCP -->
                    <div class="mb-4">
                        <label for="certRCP" class="block text-sm font-medium text-gray-700">Certificado RCP</label>
                        @if(isset($docente) && $docente->certRCP)
                            <div class="mb-2 text-sm text-blue-600">
                                <a href="{{ asset('storage/' . $docente->certRCP) }}" target="_blank" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Ver certificado actual
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="certRCP" 
                               name="certRCP" 
                               accept=".pdf,.doc,.docx"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
                        <div id="error-certRCP" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Certificado IAAS -->
                    <div class="mb-4">
                        <label for="certIAAS" class="block text-sm font-medium text-gray-700">Certificado IAAS</label>
                        @if(isset($docente) && $docente->certIAAS)
                            <div class="mb-2 text-sm text-blue-600">
                                <a href="{{ asset('storage/' . $docente->certIAAS) }}" target="_blank" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Ver certificado actual
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="certIAAS" 
                               name="certIAAS" 
                               accept=".pdf,.doc,.docx"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
                        <div id="error-certIAAS" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Acuerdo -->
                    <div class="mb-4">
                        <label for="acuerdo" class="block text-sm font-medium text-gray-700">Acuerdo</label>
                        @if(isset($docente) && $docente->acuerdo)
                            <div class="mb-2 text-sm text-blue-600">
                                <a href="{{ asset('storage/' . $docente->acuerdo) }}" target="_blank" class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    Ver acuerdo actual
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               id="acuerdo" 
                               name="acuerdo" 
                               accept=".pdf,.doc,.docx"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
                        <div id="error-acuerdo" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>
            </div>
        </form>
    </x-crud-modal>

    @vite(['resources/js/docentes.js'])
</x-app-layout>