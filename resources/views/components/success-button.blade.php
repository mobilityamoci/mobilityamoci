<button {{ $attributes->merge(['type' => 'submit',
'class' => 'justify-center inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
