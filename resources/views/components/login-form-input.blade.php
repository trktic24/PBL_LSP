@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'label' => null,
    'value' => null,
    'error' => null,
    'required' => false,
])

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-medium text-gray-500 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full rounded-[12px] border ' .
                       ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200') .
                       ' px-3 py-2.5 text-[13px] focus:outline-none focus:ring-2 transition duration-200'
        ]) }}
    />

    @if($error)
        <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
    @endif
</div>
