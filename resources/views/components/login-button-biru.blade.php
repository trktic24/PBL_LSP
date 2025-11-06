
@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge([
    'class' => 'bg-[#0960BD] hover:bg-[#0041A8] text-white font-medium px-6 py-2.5 rounded-[28px] shadow-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200 hover:-translate-y-[1px]'
]) }}>
    {{ $slot }}
</button>
