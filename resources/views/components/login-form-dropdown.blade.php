@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => null,
    'error' => null,
    'options' => [],
    'required' => false,
])

@php
    $initialValue = old($name, '');
@endphp

<div
  x-data="{
    open: false,
    selected: '{{ $initialValue }}',
    options: @js($options)
  }"
  class="relative w-full"
>
  <label class="block text-sm font-medium text-gray-700 mb-1">
    {{ $label }}
    @if($required)<span class="text-red-500">*</span>@endif
  </label>

  <input type="hidden" name="{{ $name }}" x-model="selected">

  <button
    type="button"
    @click="open = !open"
    class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
  >
    <span
      x-text="options.find(o => o.value == selected)?.label || '{{ $placeholder }}'"
      :class="{ 'text-gray-400': !selected }">
    </span>
    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div
    x-show="open"
    @click.outside="open = false"
    x-transition
    class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
    style="display: none;"
  >
    <template x-for="option in options" :key="option.value">
      <button
        type="button"
        @click="selected = option.value; open = false"
        class="block w-full text-left px-4 py-2 text-sm hover:bg-blue-500 hover:text-white transition"
        :class="{ 'bg-blue-100 text-blue-700': selected == option.value }"
        x-text="option.label"
      ></button>
    </template>
  </div>

  @if($error)
    <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
  @endif
</div>
