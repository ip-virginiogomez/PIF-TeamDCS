@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-dcs-blue-400 text-start text-base font-medium text-white bg-dcs-blue-700 focus:outline-none focus:text-white focus:bg-dcs-blue-600 focus:border-dcs-blue-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white hover:text-white hover:bg-dcs-blue-700 hover:border-dcs-blue-600 focus:outline-none focus:text-white focus:bg-dcs-blue-700 focus:border-dcs-blue-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
