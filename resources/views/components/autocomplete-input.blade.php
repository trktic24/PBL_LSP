@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'error' => null,
    'required' => false,
])

<div
    x-data="{
        query: '{{ old($name) }}',
        results: [],
        open: false,
        loading: false,

        search() {
            // Jangan cari kalo ngetik < 2 huruf
            if (this.query.length < 2) {
                this.results = [];
                this.open = false;
                return;
            }
            this.loading = true;

            // Tembak API yang tadi kita buat
            fetch(`{{ route('api.countries.search') }}?q=${this.query}`)
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                    this.open = true;
                    this.loading = false;
                });
        },

        selectItem(countryName) {
            this.query = countryName; // Isi input text yang keliatan
            this.open = false;
            this.results = [];
        }
    }"
    class="relative w-full"
>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-600 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <input
        id="{{ $id }}"
        type="text"
        x-model="query"
        @input.debounce.300ms="search()"
        @click.outside="open = false"
        @focus="search()"
        autocomplete="off"
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full rounded-xl border bg-gray-20 ' .
               ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200') .
               ' px-3 py-2.5 text-sm focus:outline-none focus:ring-2 transition duration-200 placeholder:text-gray-400'
        ]) }}
    />

    <input type="hidden" name="{{ $name }}" x-model="query">

    <div
        x-show="open"
        style="display: none;"
        x-transition
        class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden max-h-60 overflow-y-auto"
    >
        <div x-show="loading" class="p-3 text-sm text-gray-500 italic">Mencari...</div>

        <template x-if="!loading && results.length === 0 && query.length >= 2">
            <div class="p-3 text-sm text-gray-500">Negara tidak ditemukan.</div>
        </template>

        <template x-for="item in results" :key="item.id">
            <button
                type="button"
                @click="selectItem(item.name)"
                class="block w-full text-left px-4 py-2 text-sm hover:bg-blue-500 hover:text-white transition"
                x-text="item.name"
            ></button>
        </template>
    </div>

    @if($error)
        <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
    @endif
</div>
