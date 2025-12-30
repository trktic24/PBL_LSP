<div class="form-group mb-4">
    {{-- Label tetap dinamis --}}
    @if (isset($label))
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} :
        </label>
    @endif

    {{-- LOGIKA BERSYARAT: Cek apakah inputnya adalah 'textarea' --}}
    @if (isset($type) && $type === 'textarea')
        
        {{-- PERBAIKAN 1: Masukkan variable $value di ANTARA tag textarea --}}
        <textarea 
            id="{{ $id }}" 
            name="{{ $name }}" 
            rows="{{ $rows ?? 4 }}"
            class="form-input w-full border-gray-300 rounded-md shadow-sm"
        >{{ $value ?? '' }}</textarea>

    @else

        {{-- PERBAIKAN 2: Tambahkan atribut value="..." di tag input --}}
        <input 
            type="{{ $type ?? 'text' }}" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            value="{{ $value ?? '' }}" 
            class="form-input w-full border-gray-300 rounded-md shadow-sm"
        >

    @endif
</div>