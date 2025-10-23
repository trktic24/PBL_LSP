@props(['type' => 'button'])

<button {{ $attributes->merge([
    'type' => $type,
    'class' => 'w-full bg-white border border-gray-300 text-gray-700 font-medium px-6 py-3 rounded-[32px] shadow-sm flex items-center justify-center gap-3 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-200 transition duration-200 hover:-translate-y-[1px]'
]) }}>
    <img src="{{ asset('images/google-icon.svg') }}" alt="Google" class="w-5 h-5">
    <span>{{ $slot }}</span>
</button>
