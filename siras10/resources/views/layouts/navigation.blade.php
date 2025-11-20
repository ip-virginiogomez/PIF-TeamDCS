<nav x-data="{ open: false }" class="bg-dcs-blue-800 border-b border-dcs-blue-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @canany(['alumnos.read', 'docentes.read', 'carreras.read', 'sede-carrera.read'])
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión Académica</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('alumnos.read')<x-dropdown-link :href="route('alumnos.index')" wire:navigate>{{ __('Alumnos') }}</x-dropdown-link>@endcan
                                @can('docentes.read')<x-dropdown-link :href="route('docentes.index')" wire:navigate>{{ __('Docentes') }}</x-dropdown-link>@endcan
                                @can('carreras.read')<x-dropdown-link :href="route('carreras.index')" wire:navigate>{{ __('Carreras') }}</x-dropdown-link>@endcan
                                <div class="border-t border-gray-200"></div>
                                @can('sede-carrera.read')<x-dropdown-link :href="route('sede-carrera.index')" wire:navigate>{{ __('Asignar Carreras') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany

                    {{-- CAMBIO: Añadidos los permisos de sede y convenios al @canany --}}
                    @canany(['centros-formadores.read', 'tipos-centro-formador.read', 'sede.read', 'convenios.read'])
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión CF</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                {{-- CAMBIO: Añadidos @can individuales para seguridad --}}
                                @can('centros-formadores.read')<x-dropdown-link :href="route('centros-formadores.index')" wire:navigate>{{ __('Centros Formadores') }}</x-dropdown-link>@endcan
                                @can('tipos-centro-formador.read')<x-dropdown-link :href="route('tipos-centro-formador.index')" wire:navigate>{{ __('Tipos de Centro') }}</x-dropdown-link>@endcan
                                @can('sede.read')<x-dropdown-link :href="route('sede.index')" wire:navigate>{{ __('Sedes') }}</x-dropdown-link>@endcan
                                @can('convenios.read')<x-dropdown-link :href="route('convenios.index')" wire:navigate>{{ __('Convenios') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                    {{-- CAMBIO: @canany corregido con los permisos correctos --}}
                    @canany(['centro-salud.read', 'unidad-clinicas.read'])
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión CS</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                {{-- CAMBIO: Añadidos @can individuales --}}
                                @can('centro-salud.read')<x-dropdown-link :href="route('centro-salud.index')" wire:navigate>{{ __('Centros de Salud') }}</x-dropdown-link>@endcan
                                @can('unidad-clinicas.read')<x-dropdown-link :href="route('unidad-clinicas.index')" wire:navigate>{{ __('Unidades Clínicas') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                    {{-- CAMBIO: @canany corregido con los permisos correctos --}}
                    @canany(['periodos.read', 'cupo-ofertas.read', 'tipos-practica.read'])
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión de Prácticas</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                {{-- Los @can internos estaban bien --}}
                                @can('periodos.read')<x-dropdown-link :href="route('periodos.index')" wire:navigate>{{ __('Períodos') }}</x-dropdown-link>@endcan
                                @can('cupo-ofertas.read')<x-dropdown-link :href="route('cupo-ofertas.index')" wire:navigate>{{ __('Oferta de Cupos') }}</x-dropdown-link>@endcan
                                @can('tipos-practica.read')<x-dropdown-link :href="route('tipos-practica.index')" wire:navigate>{{ __('Tipos de Práctica') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany

                    @canany(['usuarios.read', 'roles.read', 'asignaciones.read']) {{-- 'asignaciones.read' es un supuesto, ajusta si es diferente --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión de Usuarios</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('usuarios.read')<x-dropdown-link :href="route('usuarios.index')" wire:navigate>{{ __('Usuarios') }}</x-dropdown-link>@endcan
                                @can('usuarios.read') {{-- Asumo que 'asignacion' depende de 'usuarios.read' --}}
                                    <x-dropdown-link :href="route('asignaciones.index')" wire:navigate>{{ __('Asignación') }}</x-dropdown-link>
                                @endcan
                                @can('roles.read')<x-dropdown-link :href="route('roles.index')" wire:navigate>{{ __('Roles') }}</x-dropdown-link>@endcan
                                <div class="border-t border-gray-200"></div>
                                @can('roles.read')<x-dropdown-link :href="route('roles.permission_matrix')" wire:navigate>{{ __('Asignar Permisos') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                            <div>{{ Auth::user()->nombreUsuario ?? Auth::user()->name }}</div>
                            <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white focus:outline-none focus:bg-dcs-blue-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-dcs-blue-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" wire:navigate :active="request()->routeIs('dashboard')" class="text-white">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @canany(['alumnos.read', 'docentes.read', 'carreras.read', 'sede-carrera.read'])
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    {{ __('Gestión Académica') }}
                </div>
                
                @can('alumnos.read')
                <x-responsive-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')" class="pl-6" wire:navigate>
                    {{ __('Alumnos') }}
                </x-responsive-nav-link>
                @endcan
                @can('docentes.read')
                <x-responsive-nav-link :href="route('docentes.index')" :active="request()->routeIs('docentes.*')" class="pl-6" wire:navigate>
                    {{ __('Docentes') }}
                </x-responsive-nav-link>
                @endcan
                @can('carreras.read')
                <x-responsive-nav-link :href="route('carreras.index')" :active="request()->routeIs('carreras.*')" class="pl-6" wire:navigate>
                    {{ __('Carreras') }}
                </x-responsive-nav-link>
                @endcan
                @can('sede-carrera.read')
                <x-responsive-nav-link :href="route('sede-carrera.index')" :active="request()->routeIs('sede-carrera.*')" class="pl-6" wire:navigate>
                    {{ __('Asignar Carreras') }}
                </x-responsive-nav-link>
                @endcan
            </div>
            @endcanany

            {{-- CAMBIO: Añadidos los permisos de sede y convenios al @canany --}}
            @canany(['centros-formadores.read', 'tipos-centro-formador.read', 'sede.read', 'convenios.read'])
                <div class="border-t border-gray-200 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        {{ __('Gestión CF') }}
                    </div>
                    @can('centros-formadores.read')
                    <x-responsive-nav-link :href="route('centros-formadores.index')" :active="request()->routeIs('centros-formadores.*')" class="pl-6" wire:navigate>
                        {{ __('Centros Formadores') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('tipos-centro-formador.read')
                    <x-responsive-nav-link :href="route('tipos-centro-formador.index')" :active="request()->routeIs('tipos-centro-formador.*')" class="pl-6" wire:navigate>
                        {{ __('Tipos de Centro') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('sede.read')
                    <x-responsive-nav-link :href="route('sede.index')" :active="request()->routeIs('sede.*')" class="pl-6" wire:navigate>
                        {{ __('Sedes') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('convenios.read')
                    <x-responsive-nav-link :href="route('convenios.index')" :active="request()->routeIs('convenios.*')" class="pl-6" wire:navigate>
                        {{ __('Convenios') }}
                    </x-responsive-nav-link>
                    @endcan
                </div>
            @endcanany

            {{-- CAMBIO: Añadido @canany y @can individuales --}}
            @canany(['centro-salud.read', 'unidad-clinicas.read'])
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    {{ __('Gestión de Salud') }}
                </div>
                @can('centro-salud.read')
                <x-responsive-nav-link :href="route('centro-salud.index')" :active="request()->routeIs('centro-salud.*')" class="pl-6" wire:navigate>
                    {{ __('Centros de Salud') }}
                </x-responsive-nav-link>
                @endcan
                @can('unidad-clinicas.read')
                <x-responsive-nav-link :href="route('unidad-clinicas.index')" :active="request()->routeIs('unidad-clinicas.*')" class="pl-6" wire:navigate>
                    {{ __('Unidades Clínicas') }}
                </x-responsive-nav-link>
                @endcan
            </div>
            @endcanany

            {{-- CAMBIO: Añadido @canany --}}
            @canany(['periodos.read', 'cupo-ofertas.read', 'tipos-practica.read'])
                <div class="border-t border-gray-200 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        {{ __('Gestión de Prácticas') }}
                    </div>
                    @can('periodos.read')
                    <x-responsive-nav-link :href="route('periodos.index')" :active="request()->routeIs('periodos.*')" class="pl-6" wire:navigate>
                        {{ __('Períodos') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('cupo-ofertas.read')
                    <x-responsive-nav-link :href="route('cupo-ofertas.index')" :active="request()->routeIs('cupo-ofertas.*')" class="pl-6" wire:navigate>
                        {{ __('Oferta de Cupos') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('tipos-practica.read')
                    <x-responsive-nav-link :href="route('tipos-practica.index')" :active="request()->routeIs('tipos-practica.*')" class="pl-6" wire:navigate>
                        {{ __('Tipos de Práctica') }}
                    </x-responsive-nav-link>
                    @endcan
                </div>
            @endcanany

            @canany(['usuarios.read', 'roles.read'])
                <div class="border-t border-gray-200 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        {{ __('Gestión de Usuarios') }}
                    </div>
                    @can('usuarios.read')
                    <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')" class="pl-6" wire:navigate>
                        {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('usuarios.read')
                    <x-responsive-nav-link :href="route('asignaciones.index')" :active="request()->routeIs('asignacion.*')" class="pl-6" wire:navigate>{{ __('Asignación') }}</x-responsive-nav-link>
                    @endcan
                    @can('roles.read')
                    <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" class="pl-6" wire:navigate>
                        {{ __('Roles') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('roles.read')
                    <x-responsive-nav-link :href="route('roles.permission_matrix')" :active="request()->routeIs('roles.permission_matrix')" class="pl-6" wire:navigate>
                        {{ __('Asignar Permisos') }}
                    </x-responsive-nav-link>
                    @endcan
                </div>
            @endcanany
        </div>

        <div class="pt-4 pb-1 border-t border-dcs-blue-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->nombreUsuario ?? Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->correo }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300" wire:navigate>{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-300">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>