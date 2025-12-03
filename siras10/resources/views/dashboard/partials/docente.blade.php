<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-indigo-700">Dashboard: Docente</h3>
        <p class="text-sm text-gray-500">Resumen rápido para docentes.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Asignaturas</div>
            <div class="text-2xl font-bold text-gray-900">—</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Grupos</div>
            <div class="text-2xl font-bold text-gray-900">—</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Estudiantes</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalAlumnos ?? '-' }}</div>
        </div>
    </div>
</div>
