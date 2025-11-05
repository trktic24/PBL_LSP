<div class="form-group mb-4">
    {{-- Label tetap dinamis --}}
    @if (isset($label))
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} :
        </label>
    @endif

    {{-- LOGIKA BERSYARAT: Cek apakah inputnya adalah 'textarea' --}}
    @if (isset($type) && $type === 'textarea')
        
        {{-- Jika TYPE adalah TEXTAREA (seperti di gambar) --}}
        <textarea 
            id="{{ $id }}" 
            name="{{ $name }}" 
            rows="{{ $rows ?? 4 }}" {{-- Tambahkan variabel ROWS, default 4, kalo mau 4 rows gausa didefinisiin lagi ketika dipanggil --}}
            class="form-input w-full border-gray-300 rounded-md shadow-sm"
        ></textarea>

    @else

        {{-- Jika TYPE adalah TEXT (default) atau tidak disetel --}}
        <input 
            type="{{ $type ?? 'text' }}" {{-- Default type adalah 'text' --}}
            id="{{ $id }}" 
            name="{{ $name }}" 
            class="form-input w-full border-gray-300 rounded-md shadow-sm"
        >

    @endif
</div>