<div class="space-y-6">
    <!-- Header / Welcome -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900">Bienvenido, {{ Auth::user()->nombreUsuario }} {{ Auth::user()->apellidoPaterno }}</h3>
        <p class="text-sm text-gray-500">Panel de Gestión - {{ $nombreCentro ?? 'Centro de Salud' }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- KPI: Alumnos en Práctica -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500 transition hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">
                        Alumnos en Práctica (Actual)
                    </div>
                    <div class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $alumnosEnPractica ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Cursando práctica en este centro
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI: Próximos Ingresos -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 transition hover:shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">
                        Próximos Ingresos (7 días)
                    </div>
                    <div class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $proximosIngresos ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Alumnos por ingresar (Credenciales)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario de Rotaciones -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Calendario de Rotaciones</h3>
        <div id="calendar"></div>
    </div>
</div>

<style>
    /* FullCalendar Customization */
    .fc-theme-standard .fc-scrollgrid {
        border: 1px solid #e5e7eb; /* gray-200 */
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937; /* gray-800 */
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .fc .fc-button-primary {
        background-color: white;
        color: #374151; /* gray-700 */
        border-color: #d1d5db; /* gray-300 */
        font-weight: 500;
        padding: 0.5rem 1rem;
        text-transform: capitalize;
        transition: all 0.2s;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .fc .fc-button-primary:hover {
        background-color: #f9fafb; /* gray-50 */
        border-color: #9ca3af; /* gray-400 */
        color: #111827; /* gray-900 */
    }

    .fc .fc-button-primary:focus {
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5); /* indigo-500 ring */
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #4f46e5; /* indigo-600 */
        border-color: #4f46e5;
        color: white;
    }
    
    .fc .fc-button-group > .fc-button {
        border-radius: 0;
    }
    
    .fc .fc-button-group > .fc-button:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .fc .fc-button-group > .fc-button:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    
    /* List View Styling */
    .fc-list-event:hover td {
        background-color: #f3f4f6;
    }
    .fc-list-day-cushion {
        background-color: #f9fafb !important;
    }
</style>

<!-- FullCalendar CDN -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'listMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Agenda'
            },
            events: {!! json_encode($calendarEvents ?? []) !!},
            eventDidMount: function(info) {
                info.el.title = info.event.extendedProps.institucion + 
                                '\n' + info.event.extendedProps.carrera + 
                                '\n' + info.event.extendedProps.unidad;
            }
        });
        calendar.render();
    });
</script>
