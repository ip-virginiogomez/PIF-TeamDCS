<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black-800 dark:text-black-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @includeIf('dashboard.partials.' . ($dashboardVariant ?? 'default'))
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.cuposPorCarreraLabels = {!! json_encode($cuposPorCarreraLabels ?? []) !!};
        window.cuposPorCarreraData = {!! json_encode($cuposPorCarreraData ?? []) !!};
        window.inmunizacionData = {!! json_encode($inmunizacionData ?? []) !!};
        window.docenteInmunizacionData = {!! json_encode($docenteInmunizacionData ?? []) !!};
        window.ocupacionLabels = {!! json_encode($ocupacionLabels ?? []) !!};
        window.ocupacionTotal = {!! json_encode($ocupacionTotal ?? []) !!};
        window.ocupacionAsignada = {!! json_encode($ocupacionAsignada ?? []) !!};
    </script>
</x-app-layout>