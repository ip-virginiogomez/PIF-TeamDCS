<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mt-4">
            <x-input-label for="runUsuario" :value="__('RUN')" />
            <x-text-input id="runUsuario" class="block mt-1 w-full" type="text" name="runUsuario" :value="old('runUsuario')" required autofocus />
            <x-input-error :messages="$errors->get('runUsuario')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="nombreUsuario" :value="__('Nombre de Usuario')" />
            <x-text-input id="nombreUsuario" class="block mt-1 w-full" type="text" name="nombreUsuario" :value="old('nombreUsuario')" required />
            <x-input-error :messages="$errors->get('nombreUsuario')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="correo" :value="__('Email')" />
            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required />
            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
