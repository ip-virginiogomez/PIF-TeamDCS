@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 bg-gray-100 text-gray-900 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-md shadow-sm']) !!}>
