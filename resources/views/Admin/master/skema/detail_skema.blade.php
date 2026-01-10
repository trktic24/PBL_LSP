<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $skema->nama_skema }} | Detail Skema</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 0;
        }

        [x-cloak] {
            display: none !important;
        }

        .fixed-sidebar-icon {
            width: 60px;
            top: 85px;
            left: 5px;
        }

        .content-area {
            padding-left: 96px;
            min-height: 100vh;
        }

        .nav-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <x-navbar.navbar-admin/>
    

    <main class="content-area p-6">

        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.master_skema') }}" class="hover:text-blue-600">Master Skema</a> / Detail
            </p>
            <h2 class="text-3xl font-bold text-gray-900">Detail Skema Sertifikasi</h2>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 10000)" 
                 class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center shadow-sm" 
                 role="alert">
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @php
            $bgImage = $skema->gambar ? asset('storage/' . $skema->gambar) : asset('images/default.jpg');
        @endphp
        <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 border border-gray-200">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $bgImage }}'); filter: brightness(0.3);"></div>

            <div class="relative p-6 md:p-10 text-white flex flex-col justify-end min-h-[300px]">
                <div class="absolute top-4 right-4 text-base font-semibold px-3 py-1 rounded-full bg-blue-600/80 backdrop-blur-sm">
                    {{ $skema->category?->nama_kategori ?? 'Tanpa Kategori' }}
                </div>

                <h1 class="text-4xl font-extrabold mb-2 break-words max-w-3xl">
                    {{ $skema->nama_skema }}
                </h1>
                <p class="text-lg font-medium opacity-80 mb-4">{{ $skema->nomor_skema }}</p>

                <p class="text-sm italic opacity-90 mb-4 max-w-4xl">
                    {{ $skema->deskripsi_skema }}
                </p>

                <div class="text-2xl font-bold text-white">
                    Rp {{ number_format($skema->harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 mb-8"
             x-data="formConfigHandler()">

            <form id="formConfigUpdate" action="{{ route('admin.skema.detail.update_form', $skema->id_skema) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Konfigurasi Formulir Asesmen</h3>
                        <p class="text-sm text-gray-500 mt-1">Centang formulir yang wajib digunakan pada skema ini.</p>
                    </div>

                    <button type="submit"
                        :class="hasChanges 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white shadow cursor-pointer transform hover:scale-105' 
                                : 'bg-gray-200 text-gray-400 cursor-not-allowed shadow-none'"
                        :disabled="!hasChanges"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-700 font-semibold uppercase">
                            <tr class="divide-x divide-gray-200">
                                <th class="px-6 py-3 w-16 text-center">
                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="px-6 py-3">Kode Form</th>
                                <th class="px-6 py-3">Nama Formulir</th>
                                <th class="px-6 py-3 text-center w-32">Status</th>
                                <th class="px-6 py-3 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template x-for="(form, index) in forms" :key="index">
                                <tr class="hover:bg-gray-50 transition divide-x divide-gray-200" :class="form.checked ? '' : 'opacity-60 bg-gray-50'">
                                    
                                    <td class="px-6 py-3 text-center">
                                        <input type="checkbox" 
                                               :name="form.db_field" 
                                               x-model="form.checked" 
                                               @change="updateCheckAll()"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                    </td>

                                    <td class="px-6 py-3 font-mono text-gray-800" x-text="form.code"></td>
                                    <td class="px-6 py-3 text-gray-800" x-text="form.name"></td>
                                    
                                    <td class="px-6 py-3 text-center">
                                        <span x-show="form.checked" class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium border border-green-200">
                                            Aktif
                                        </span>
                                        <span x-show="!form.checked" class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full font-medium border border-gray-200">
                                            Non-Aktif
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-3 text-center">
                                        <template x-if="form.url">
                                            <a :href="form.url" class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                <i class="fas fa-edit mr-2"></i> Kelola
                                            </a>
                                        </template>
                                        <template x-if="!form.url">
                                            <div class="flex flex-col items-center">
                                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Belum Tersedia</span>
                                                <div class="h-1 w-8 bg-gray-100 rounded-full mt-1"></div>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Kelompok Pekerjaan & Unit Kompetensi</h3>
                
                @if($skema->kelompokPekerjaan->isNotEmpty())
                    @php $kelompokId = $skema->kelompokPekerjaan->first()->id_kelompok_pekerjaan; @endphp
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.skema.detail.edit_kelompok', $kelompokId) }}" 
                           class="flex items-center space-x-1 px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition shadow-sm">
                            <i class="fas fa-pen text-xs"></i> <span>Edit</span>
                        </a>

                        <form action="{{ route('admin.skema.detail.destroy_kelompok', $kelompokId) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus Kelompok Pekerjaan ini beserta seluruh Unit Kompetensinya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center space-x-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition shadow-sm">
                                <i class="fas fa-trash text-xs"></i> <span>Delete</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <table class="min-w-full text-sm text-left border border-gray-200 border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-4 py-3 font-semibold border border-gray-200 w-1/4">Kelompok Pekerjaan</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200 w-12 text-center">No</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200 w-42">Kode Unit</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200">Judul Unit</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200 w-20 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @php 
                        $globalNo = 1; 
                    @endphp
                    
                    @forelse ($skema->kelompokPekerjaan as $kelompok)
                        @php
                            $jumlahUnit = $kelompok->unitKompetensi->count();
                            $units = $kelompok->unitKompetensi->sortBy('urutan');
                        @endphp

                        @if($jumlahUnit > 0)
                            @foreach($units as $index => $unit)
                                @php $currentNo = $globalNo++; @endphp

                                <tr class="hover:bg-gray-50 transition">
                                    
                                    @if($index === 0)
                                        <td class="px-4 py-4 font-bold align-top border border-gray-200 text-gray-900" 
                                            rowspan="{{ $jumlahUnit }}">
                                            {{ $kelompok->nama_kelompok_pekerjaan }}
                                        </td>
                                    @endif
                                    
                                    <td class="px-4 py-3 text-center border border-gray-200">{{ $currentNo }}.</td>
                                    
                                    <td class="px-4 py-3 font-mono text-gray-600 whitespace-nowrap border border-gray-200">{{ $unit->kode_unit }}</td>
                                    <td class="px-4 py-3 text-gray-800 border border-gray-200">{{ $unit->judul_unit }}</td>
                                    
                                    <td class="px-4 py-3 text-center border border-gray-200">
                                        <form action="{{ route('admin.skema.detail.destroy_unit', ['id_unit' => $unit->id_unit_kompetensi, 'no' => $currentNo]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus Unit No. {{ $currentNo }} @if($unit->kode_unit)({{ $unit->kode_unit }})@endif?');">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <div class="flex justify-center">
                                                <button type="submit" 
                                                        class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-md hover:bg-red-100 hover:text-red-700 transition shadow-sm border border-red-100" 
                                                        title="Hapus Unit">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-4 font-bold align-top bg-gray-50 border border-gray-200 text-gray-900">
                                    {{ $kelompok->nama_kelompok_pekerjaan }}
                                </td>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-400 italic border border-gray-200">
                                    Belum ada unit kompetensi.
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center border border-gray-200 bg-gray-50/50">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-layer-group text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada Kelompok Pekerjaan yang ditambahkan pada skema ini.</p>
                                    
                                    <a href="{{ route('admin.skema.detail.add_kelompok', $skema->id_skema) }}" 
                                       class="mt-4 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition-transform transform hover:-translate-y-1 flex items-center">
                                        <i class="fas fa-plus-circle mr-2 text-lg"></i>
                                        Tambah Kelompok & Unit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

    <script>
        function formConfigHandler() {
            return {
                // Mengambil data yang sudah di-format dari Controller
                forms: @json($formConfig ?? []),

                checkAll: false,
                originalForms: [],

                init() {
                    if (!this.forms || this.forms.length === 0) return;
                    
                    // Simpan state awal untuk deteksi perubahan
                    this.originalForms = JSON.parse(JSON.stringify(this.forms));
                    
                    // Set status Check All di awal
                    this.updateCheckAll();
                },

                get hasChanges() {
                    // Bandingkan state saat ini dengan state awal
                    return JSON.stringify(this.forms) !== JSON.stringify(this.originalForms);
                },

                toggleAll() {
                    this.forms.forEach(f => f.checked = this.checkAll);
                },

                updateCheckAll() {
                    this.checkAll = this.forms.length > 0 && this.forms.every(f => f.checked);
                }
            }
        }
    </script>

</body>

</html>