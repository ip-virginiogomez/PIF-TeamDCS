<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black-800 dark:text-black-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
                <!-- KPI: Total Alumnos -->
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center border-t-4 border-indigo-400 hover:scale-105 transition-transform">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-1">{{ $totalAlumnos ?? '-' }}</div>
                    <div class="text-sm text-gray-500 tracking-wide">Alumnos</div>
                </div>
                <!-- KPI: Total Docentes -->
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center border-t-4 border-indigo-400 hover:scale-105 transition-transform">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-1">{{ $totalDocentes ?? '-' }}</div>
                    <div class="text-sm text-gray-500 tracking-wide">Docentes</div>
                </div>
                <!-- KPI: Total Cupos Ofertados -->
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center border-t-4 border-indigo-400 hover:scale-105 transition-transform">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-1">{{ $totalCupos ?? '-' }}</div>
                    <div class="text-sm text-gray-500 tracking-wide">Cupos ofertados</div>
                </div>
                <!-- KPI: Total Usuarios -->
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center border-t-4 border-indigo-400 hover:scale-105 transition-transform">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-1">{{ $totalUsuarios ?? '-' }}</div>
                    <div class="text-sm text-gray-500 tracking-wide">Usuarios</div>
                </div>
                <!-- KPI: Total Centros Formadores -->
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center border-t-4 border-indigo-400 hover:scale-105 transition-transform">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-1">{{ $totalCentrosFormadores ?? '-' }}</div>
                    <div class="text-sm text-gray-500 tracking-wide">Centros formadores</div>
                </div>
            </div>
            <div class="bg-white shadow-xl rounded-2xl p-8 mt-8 border-t-4 border-indigo-400">
                <h3 class="text-xl font-semibold text-indigo-700 mb-6 text-center tracking-wide">Cupos ofertados por carrera</h3>
                <canvas id="cuposPorCarreraChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.cuposPorCarreraLabels = {!! json_encode($cuposPorCarreraLabels ?? []) !!};
        window.cuposPorCarreraData = {!! json_encode($cuposPorCarreraData ?? []) !!};
    </script>
    <script src="{{ asset('resources/js/app.js') }}"></script>
</x-app-layout>