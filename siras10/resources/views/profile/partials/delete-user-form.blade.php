{{-- resources/views/profile/partials/delete-user-form.blade.php --}}
<section>
    <header class="border-b border-red-200 pb-4 mb-6">
        <h2 class="text-lg font-semibold text-red-800">
            Eliminar Cuenta
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        Eliminar Cuenta
    </button>

    {{-- Modal de Confirmación --}}
    <div 
        x-data="{ show: false }" 
        x-on:open-modal.window="show = ($event.detail === 'confirm-user-deletion')"
        x-on:close.window="show = false"
        x-show="show"
        class="fixed inset-0 overflow-y-auto z-50"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            {{-- Overlay --}}
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                x-on:click="show = false"
            ></div>

            {{-- Modal Content --}}
            <div 
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
            >
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">
                            ¿Estás seguro de que deseas eliminar tu cuenta?
                        </h3>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.
                    </p>

                    <div class="mb-6">
                        <label for="password" class="sr-only">Contraseña</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Contraseña"
                        />
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button"
                            x-on:click="show = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Eliminar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
