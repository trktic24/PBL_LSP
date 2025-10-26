@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'required' => false,
])
<div x-data="{ fileName: '' }"
     class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 border border-gray-200 rounded-xl gap-3 hover:shadow-sm hover:border-blue-400 bg-gray-50 transition duration-200">

    <label for="{{ $id }}" class="text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
        <span class="text-sm text-gray-500 truncate max-w-[180px]">
            <template x-if="fileName === ''">
                <span>Tidak ada berkas</span>
            </template>
            <template x-if="fileName !== ''">
                <span x-text="fileName"></span>
            </template>
        </span>

        <input type="file" name="{{ $name }}" id="{{ $id }}" class="hidden"
               @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''"
               {{ $required ? 'required' : '' }}>

        <label for="{{ $id }}" class="cursor-pointer bg-white border border-gray-300 hover:border-blue-400 text-gray-700 font-medium py-2 px-4 rounded-xl text-sm transition-all duration-200 hover:bg-blue-50 hover:text-blue-600">
            Pilih File
        </label>
    </div>
</div>
