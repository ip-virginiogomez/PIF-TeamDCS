{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<section>
    <header class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-lg font-semibold text-gray-800">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Actualiza la información de tu cuenta y dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        {{-- Fila 1: RUN y Nombre --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- RUN (No editable) --}}
            <div>
                <label for="runUsuario" class="block text-sm font-medium text-gray-700">RUN</label>
                <input 
                    id="runUsuario" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm cursor-not-allowed" 
                    value="{{ $user->runUsuario }}" 
                    disabled
                />
                <p class="mt-1 text-xs text-gray-500">El RUN no puede ser modificado</p>
            </div>

            {{-- Nombre --}}
            <div>
                <label for="nombreUsuario" class="block text-sm font-medium text-gray-700">Nombre *</label>
                <input 
                    id="nombreUsuario" 
                    name="nombreUsuario" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    value="{{ old('nombreUsuario', $user->nombreUsuario) }}" 
                    required 
                    autofocus 
                />
                @error('nombreUsuario')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fila 2: Apellidos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Apellido Paterno --}}
            <div>
                <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
                <input 
                    id="apellidoPaterno" 
                    name="apellidoPaterno" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    value="{{ old('apellidoPaterno', $user->apellidoPaterno) }}" 
                    required
                />
                @error('apellidoPaterno')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Apellido Materno --}}
            <div>
                <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno *</label>
                <input 
                    id="apellidoMaterno" 
                    name="apellidoMaterno" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    value="{{ old('apellidoMaterno', $user->apellidoMaterno) }}" 
                    required
                />
                @error('apellidoMaterno')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fila 3: Correo y Teléfono --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Correo Electrónico --}}
            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                <input 
                    id="correo" 
                    name="correo" 
                    type="email" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    value="{{ old('correo', $user->correo) }}" 
                    required 
                    autocomplete="email"
                />
                @error('correo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            Tu dirección de correo electrónico no está verificada.
                            <button 
                                form="send-verification" 
                                class="underline text-sm text-yellow-800 hover:text-yellow-900 font-medium"
                            >
                                Haz clic aquí para reenviar el correo de verificación.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-green-600 font-medium">
                                Se ha enviado un nuevo enlace de verificación a tu correo.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Teléfono --}}
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input 
                    id="telefono" 
                    name="telefono" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    value="{{ old('telefono', $user->telefono) }}" 
                    placeholder="+56912345678"
                />
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fila 4: Tipo de Personal y Fecha de Registro --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Tipo de Personal de Salud (No editable) --}}
            @if($user->tipoPersonalSalud)
            <div>
                <label for="tipoPersonalSalud" class="block text-sm font-medium text-gray-700">Tipo de Personal</label>
                <input 
                    id="tipoPersonalSalud" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm cursor-not-allowed" 
                    value="{{ $user->tipoPersonalSalud->nombreTipoPersonalSalud ?? 'No especificado' }}" 
                    disabled
                />
                <p class="mt-1 text-xs text-gray-500">El tipo de personal solo puede ser modificado por un administrador</p>
            </div>
            @endif

            {{-- Fecha de Creación (No editable) --}}
            <div>
                <label for="fechaCreacion" class="block text-sm font-medium text-gray-700">Fecha de Registro</label>
                <input 
                    id="fechaCreacion" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm cursor-not-allowed" 
                    value="{{ $user->fechaCreacion ? \Carbon\Carbon::parse($user->fechaCreacion)->format('d/m/Y H:i') : 'No disponible' }}" 
                    disabled
                />
            </div>
        </div>

        {{-- Roles Asignados (No editable) --}}
        @if($user->roles->isNotEmpty())
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Roles Asignados</label>
            <div class="flex flex-wrap gap-2">
                @foreach($user->roles as $role)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $role->name }}
                    </span>
                @endforeach
            </div>
            <p class="mt-1 text-xs text-gray-500">Los roles solo pueden ser modificados por un administrador</p>
        </div>
        @endif

        {{-- Centros Formadores (Si es Coordinador) --}}
        @if($user->esCoordinador() && $user->centrosFormadores->isNotEmpty())
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Centros Formadores Asignados</label>
            <div class="space-y-1">
                @foreach($user->centrosFormadores as $centro)
                    <div class="flex items-center p-2 bg-gray-50 rounded">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $centro->nombreCentroFormador }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Centros de Salud (Si es Personal) --}}
        @if($user->centroSalud->isNotEmpty())
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Centros de Salud Asignados</label>
            <div class="space-y-1">
                @foreach($user->centroSalud as $centro)
                    <div class="flex items-center p-2 bg-gray-50 rounded">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $centro->nombreCentroSalud }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div class="flex items-center gap-4">
                <button 
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Guardar Cambios
                </button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 font-medium"
                    >
                        ✓ Guardado correctamente
                    </p>
                @endif
            </div>
        </div>
    </form>
</section>
