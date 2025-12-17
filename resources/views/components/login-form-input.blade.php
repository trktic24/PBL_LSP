@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'label' => null,
    'value' => null,
    'error' => null,
    'required' => false,
    'readonly' => false,
])

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 mb-1">
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
    {{ $readonly ? 'readonly' : '' }}
    {{ $attributes->merge([
        'class' => 'mt-1 block w-full rounded-xl border bg-gray-20 ' .
           ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200') .
           ' px-3 py-2.5 text-sm focus:outline-none focus:ring-2 transition duration-200 placeholder:text-gray-400'
        ]) }}
    x-bind:class="{
        '!border-red-500 !focus:border-red-500 !focus:ring-red-200': typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['{{ $name }}'] && errors['{{ $name }}'],
        '!border-gray-300 !focus:border-blue-500 !focus:ring-blue-200': typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['{{ $name }}'] && !errors['{{ $name }}'] && !'{{ $error }}'
    }"
    />


    @if($error)
        <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
    @endif
    <p x-show="typeof touched !== 'undefined' && typeof errors !== 'undefined' && touched['{{ $name }}'] && errors['{{ $name }}']"
       x-text="errors['{{ $name }}']"
       class="text-xs text-red-500 mt-1"
       style="display: none;">
    </p>
</div>
