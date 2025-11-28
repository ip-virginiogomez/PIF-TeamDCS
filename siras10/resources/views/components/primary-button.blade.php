<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-dcs-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-dcs-blue-700 focus:bg-dcs-blue-700 active:bg-dcs-blue-800 focus:outline-none focus:ring-2 focus:ring-dcs-blue-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
