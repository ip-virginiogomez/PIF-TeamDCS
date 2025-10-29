<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo de Centro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('tipos-centro-formador.update', $tipos_centro_formador) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('tipos-centro._form', ['tipo' => $tipos_centro_formador])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>