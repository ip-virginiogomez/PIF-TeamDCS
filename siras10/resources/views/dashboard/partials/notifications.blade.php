@if(isset($notificaciones) && $notificaciones->count() > 0)
    <div class="mb-6 bg-white shadow rounded-lg p-4 border-l-4 border-yellow-500">
        <h4 class="text-md font-bold text-gray-800 mb-2">Notificaciones</h4>
        <ul class="space-y-2">
            @foreach($notificaciones as $notificacion)
                <li class="flex items-start p-2 bg-yellow-50 rounded-md">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <div>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $notificacion->data['mensaje'] }}</p>
                        <span class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($notificacion->created_at)->format('d/m/Y H:i') }}
                            ({{ \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() }})
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $notificaciones->links() }}
        </div>
    </div>
@endif
