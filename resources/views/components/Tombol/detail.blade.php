@props(['type' => 'button'])

<button {{ $attributes->merge([
    'type' => $type,
    'class' => 'bg-[#FFD700] hover:bg-[#F5C400] text-[#002B5B] font-semibold px-8 py-2 rounded-full shadow-[0_3px_6px_rgba(0,0,0,0.1)] flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-transform transform duration-200 ease-in-out hover:scale-105 hover:shadow-[0_5px_10px_rgba(0,0,0,0.15)]'
]) }}>
    {{ $slot }}
</button>
