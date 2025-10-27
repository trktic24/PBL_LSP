@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Pilih opsi',
    'error' => null,
    'required' => false,
    'options' => ['Laki-laki', 'Perempuan'], // Default options
])

@php
    $initialValue = old($name, '');
@endphp

<div
    x-data="{ open: false, selected: '{{ $initialValue }}', options: @js($options) }"
    class="relative w-full"
>
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Hidden input buat submit ke backend --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-model="selected" {{ $required ? 'required' : '' }}>

    {{-- Button Dropdown --}}
    <button
        type="button"
        @click="open = !open"
        class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 bg-white border rounded-lg shadow-sm transition focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
        :class="{
            'border-gray-300': !{{ $error ? 'true' : 'false' }},
            'border-red-500': {{ $error ? 'true' : 'false' }}
        }"
        :aria-expanded="open"
    >
        <span x-text="selected || '{{ $placeholder }}'" :class="{ 'text-gray-400': !selected }"></span>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown List --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
        style="display: none;"
    >
        <template x-for="option in options" :key="option">
            <button
                type="button"
                @click="selected = option; open = false"
                class="block w-full text-left px-4 py-2 text-sm transition"
                :class="selected === option
                    ? 'bg-blue-100 text-blue-700'
                    : 'hover:bg-blue-500 hover:text-white'"
                x-text="option"
            ></button>
        </template>
    </div>

    {{-- Error Message --}}
    @if($error)
        <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
    @endif
</div>
