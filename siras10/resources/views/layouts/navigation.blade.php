<nav x-data="{ open: false }" class="bg-dcs-blue-800 border-b border-dcs-blue-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Menú Gestión Académica -->
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
                                @can('alumnos.read')<x-dropdown-link :href="route('alumnos.index')">{{ __('Alumnos') }}</x-dropdown-link>@endcan
                                @can('docentes.read')<x-dropdown-link :href="route('docentes.index')">{{ __('Docentes') }}</x-dropdown-link>@endcan
                                @can('carreras.read')<x-dropdown-link :href="route('carreras.index')">{{ __('Carreras') }}</x-dropdown-link>@endcan
                                <div class="border-t border-gray-200"></div>
                                @can('sede-carrera.read')<x-dropdown-link :href="route('sede-carrera.index')">{{ __('Asignar Carreras') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany

                    <!-- Menú Gestión Centro Formador -->
                    @canany(['centros-formadores.read', 'tipos-centro-formador.read'])
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión CF</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('centros-formadores.index')">{{ __('Centros Formadores') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('tipos-centro-formador.index')">{{ __('Tipos de Centro') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('sede.index')">{{ __('Sedes') }}</x-dropdown-link>
                                <!-- Convenios permanece aquí -->
                                <x-dropdown-link :href="route('convenios.index')">{{ __('Convenios') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                    <!-- ===== FIN: MENÚ DE GESTIÓN CENTRO FORMADOR ===== -->
                    
                    <!-- ===== INICIO: MENÚ DE GESTIÓN DE SALUD ===== -->
                    @canany(['roles.read', 'usuarios.read'])
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión CS</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('centro-salud.read')<x-dropdown-link :href="route('centro-salud.index')">{{ __('Centros de Salud') }}</x-dropdown-link>@endcan
                                @can('unidad-clinicas.read')<x-dropdown-link :href="route('unidad-clinicas.index')">{{ __('Unidades Clínicas') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                    <!-- ===== FIN: MENÚ DE GESTIÓN DE SALUD ===== -->

                    <!-- ===== INICIO: MENÚ DE GESTIÓN DE Prácticas ===== -->
                    @canany(['roles.read', 'usuarios.read'])
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión de Prácticas</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('periodos.read')<x-dropdown-link :href="route('periodos.index')">{{ __('Períodos') }}</x-dropdown-link>@endcan
                                @can('cupo-ofertas.read')<x-dropdown-link :href="route('cupo-ofertas.index')">{{ __('Oferta de Cupos') }}</x-dropdown-link>@endcan
                                @can('tipos-practica.read')<x-dropdown-link :href="route('tipos-practica.index')">{{ __('Tipos de Práctica') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany

                    <!-- Menú Gestión de Usuarios -->
                    @canany(['usuarios.read', 'roles.read'])
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                                    <div>Gestión de Usuarios</div>
                                    <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @can('usuarios.read')<x-dropdown-link :href="route('usuarios.index')">{{ __('Usuarios') }}</x-dropdown-link>@endcan
                                @can('usuarios.read')
                                    <x-dropdown-link :href="route('asignaciones.index')">{{ __('Asignación') }}</x-dropdown-link>
                                @endcan
                                @can('roles.read')<x-dropdown-link :href="route('roles.index')">{{ __('Roles') }}</x-dropdown-link>@endcan
                                <div class="border-t border-gray-200"></div>
                                @can('roles.read')<x-dropdown-link :href="route('roles.permission_matrix')">{{ __('Asignar Permisos') }}</x-dropdown-link>@endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcanany
                </div>
            </div>

            <!-- Settings Dropdown (Menú de Usuario) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-dcs-blue-800 hover:bg-dcs-blue-700 focus:outline-none transition">
                            <div>{{ Auth::user()->nombreUsuario ?? Auth::user()->name }}</div>
                            <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white focus:outline-none focus:bg-dcs-blue-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-dcs-blue-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Gestión Académica - Responsive -->
            @canany(['alumnos.read', 'docentes.read', 'carreras.read', 'sede-carrera.read'])
            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    {{ __('Gestión Académica') }}
                </div>
                
                @can('alumnos.read')
                <x-responsive-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')" class="pl-6">
                    {{ __('Alumnos') }}
                </x-responsive-nav-link>
                @endcan

                @can('docentes.read')
                <x-responsive-nav-link :href="route('docentes.index')" :active="request()->routeIs('docentes.*')" class="pl-6">
                    {{ __('Docentes') }}
                </x-responsive-nav-link>
                @endcan

                @can('carreras.read')
                <x-responsive-nav-link :href="route('carreras.index')" :active="request()->routeIs('carreras.*')" class="pl-6">
                    {{ __('Carreras') }}
                </x-responsive-nav-link>
                @endcan

                @can('sede-carrera.read')
                <x-responsive-nav-link :href="route('sede-carrera.index')" :active="request()->routeIs('sede-carrera.*')" class="pl-6">
                    {{ __('Asignar Carreras') }}
                </x-responsive-nav-link>
                @endcan
            </div>
            @endcanany

            <div class="border-t border-gray-200 pt-2">
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    {{ __('Gestión de Salud') }}
                </div>
                <x-responsive-nav-link :href="route('centro-salud.index')" :active="request()->routeIs('centro-salud.*')" class="pl-6">
                    {{ __('Centros de Salud') }}
                </x-responsive-nav-link>
            </div>

            @canany(['roles.read', 'usuarios.read'])
                <div class="border-t border-gray-200 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        {{ __('Gestión de Usuarios') }}
                    </div>
                    <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" class="pl-6">
                        {{ __('Roles') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')" class="pl-6">
                        {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('roles.permission_matrix')" :active="request()->routeIs('roles.permission_matrix')" class="pl-6">
                        {{ __('Asignar Permisos') }}
                    </x-responsive-nav-link>
                </div>
            @endcanany
        </div>

        <!-- SECCIONES MÓVIL REORGANIZADAS Y PROTEGIDAS -->
        @canany(['alumnos.read', 'docentes.read', 'carreras.read'])
            <div class="pt-4 pb-1 border-t border-dcs-blue-700">
                <div class="px-4">
                    <div class="font-medium text-base text-white">Gestión Académica</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('alumnos.read')<x-responsive-nav-link :href="route('alumnos.index')" class="text-gray-300">{{ __('Alumnos') }}</x-responsive-nav-link>@endcan
                    @can('docentes.read')<x-responsive-nav-link :href="route('docentes.index')" class="text-gray-300">{{ __('Docentes') }}</x-responsive-nav-link>@endcan
                    @can('carreras.read')<x-responsive-nav-link :href="route('carreras.index')" class="text-gray-300">{{ __('Carreras') }}</x-responsive-nav-link>@endcan
                </div>
            </div>
        @endcanany

        @canany(['centros-formadores.read', 'tipos-centro-formador.read'])
            <div class="pt-4 pb-1 border-t border-dcs-blue-700">
                <div class="px-4">
                    <div class="font-medium text-base text-white">Gestión CF</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('centros-formadores.read')<x-responsive-nav-link :href="route('centros-formadores.index')" class="text-gray-300">{{ __('Centros Formadores') }}</x-responsive-nav-link>@endcan
                    @can('tipos-centro-formador.read')<x-responsive-nav-link :href="route('tipos-centro-formador.index')" class="text-gray-300">{{ __('Tipos de Centro (CF)') }}</x-responsive-nav-link>@endcan
                </div>
            </div>
        @endcanany

        @canany(['centro-salud.read', 'unidad-clinicas.read'])
            <div class="pt-4 pb-1 border-t border-dcs-blue-700">
                <div class="px-4">
                    <div class="font-medium text-base text-white">Gestión CS</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('centro-salud.read')<x-responsive-nav-link :href="route('centro-salud.index')" class="text-gray-300">{{ __('Centros de Salud') }}</x-responsive-nav-link>@endcan
                    @can('unidad-clinicas.read')<x-responsive-nav-link :href="route('unidad-clinicas.index')" class="text-gray-300">{{ __('Unidades Clínicas') }}</x-responsive-nav-link>@endcan
                </div>
            </div>
        @endcanany
        
        @canany(['periodos.read', 'cupo-ofertas.read'])
            <div class="pt-4 pb-1 border-t border-dcs-blue-700">
                <div class="px-4">
                    <div class="font-medium text-base text-white">Gestión de Prácticas</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('periodos.read')<x-responsive-nav-link :href="route('periodos.index')" class="text-gray-300">{{ __('Períodos') }}</x-responsive-nav-link>@endcan
                    @can('cupo-ofertas.read')<x-responsive-nav-link :href="route('cupo-ofertas.index')" class="text-gray-300">{{ __('Oferta de Cupos') }}</x-responsive-nav-link>@endcan
                    @can('tipos-practica.read')<x-responsive-nav-link :href="route('tipos-practica.index')" class="text-gray-300">{{ __('Tipos de Práctica') }}</x-responsive-nav-link>@endcan
                </div>
            </div>
        @endcanany

        @canany(['usuarios.read', 'roles.read'])
            <div class="pt-4 pb-1 border-t border-dcs-blue-700">
                <div class="px-4">
                    <div class="font-medium text-base text-white">Gestión de Usuarios</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('usuarios.read')<x-responsive-nav-link :href="route('usuarios.index')" class="text-gray-300">{{ __('Usuarios') }}</x-responsive-nav-link>@endcan
                    @can('roles.read')<x-responsive-nav-link :href="route('roles.index')" class="text-gray-300">{{ __('Roles') }}</x-responsive-nav-link>@endcan
                    @can('usuarios.read')
                        <x-responsive-nav-link :href="route('asignaciones.index')" :active="request()->routeIs('asignacion.*')" class="pl-6">{{ __('Asignación') }}</x-responsive-nav-link>
                    @endcan
                    @can('roles.read')<x-responsive-nav-link :href="route('roles.permission_matrix')" class="text-gray-300">{{ __('Asignar Permisos') }}</x-responsive-nav-link>@endcan
                </div>
            </div>
        @endcanany

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-dcs-blue-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->nombreUsuario ?? Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->correo }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-300">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>