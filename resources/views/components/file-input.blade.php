@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'required' => false,
])

<div x-data="{ fileName: '' }"
     class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 border border-gray-300 rounded-lg gap-3 hover:shadow-md hover:border-blue-400 transition">

    <label for="{{ $id }}" class="text-base font-medium text-gray-900">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
        <span class="text-sm text-gray-500">
            <template x-if="fileName === ''">
                <span>Tidak ada berkas yang diupload</span>
            </template>
            <template x-if="fileName !== ''">
                <span x-text="fileName"></span>
            </template>
        </span>
        <input type="file" name="{{ $name }}" id="{{ $id }}" class="hidden"
               @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''"
               {{ $required ? 'required' : '' }}>

        <label for="{{ $id }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm transition-colors border border-gray-500">
            Pilih File
        </label>
    </div>
</div>
