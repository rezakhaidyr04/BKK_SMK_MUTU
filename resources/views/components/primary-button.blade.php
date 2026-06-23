<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition']) }}>
    {{ $slot }}
</button>
