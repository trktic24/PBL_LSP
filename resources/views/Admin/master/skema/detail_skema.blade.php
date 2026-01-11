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
                                        <div class="flex items-center justify-center gap-2">
                                            <template x-if="form.url">
                                                <a :href="form.url" class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs font-bold transition-all shadow-sm" title="Kelola Template">
                                                    <i class="fas fa-cog mr-2"></i> Kelola
                                                </a>
                                            </template>
                                            
                                            <template x-if="form.admin_url">
                                                <a :href="form.admin_url" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 rounded-lg text-xs font-bold transition-all shadow-sm" title="Lihat Hasil Asesi">
                                                    <i class="fas fa-eye mr-2"></i> Lihat
                                                </a>
                                            </template>

                                            <template x-if="!form.url && !form.admin_url">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Belum Tersedia</span>
                                                    <div class="h-1 w-8 bg-gray-100 rounded-full mt-1"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6" x-data="kelompokHandler()">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h3 class="text-xl font-semibold text-gray-900">Kelompok Pekerjaan & Unit Kompetensi</h3>
                
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-grow md:flex-grow-0">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" x-model="searchQuery" 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full md:w-64" 
                               placeholder="Cari unit atau kelompok...">
                    </div>

                    <!-- Expand/Collapse All -->
                    <button @click="toggleAll()" 
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-medium transition"
                            x-text="allExpanded ? 'Tutup Semua' : 'Buka Semua'">
                    </button>

                    <!-- Add Button -->
                    <a href="{{ route('admin.skema.detail.add_kelompok', $skema->id_skema) }}" 
                       class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        <i class="fas fa-plus-circle"></i> <span>Tambah Kelompok</span>
                    </a>
                </div>
            </div>

            <!-- List Container -->
            <div class="space-y-4">
                <template x-for="kelompok in filteredKelompok" :key="kelompok.id_kelompok_pekerjaan">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Group Header -->
                        <div class="bg-gray-50 border-b border-gray-200 p-4 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition"
                             @click="toggleGroup(kelompok.id_kelompok_pekerjaan)">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-chevron-right text-gray-400 transform transition-transform duration-300"
                                   :class="isExpanded(kelompok.id_kelompok_pekerjaan) ? 'rotate-90' : ''"></i>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg" x-text="kelompok.nama_kelompok_pekerjaan"></h4>
                                    <span class="text-xs text-gray-500" x-text="kelompok.unit_kompetensi.length + ' Unit Kompetensi'"></span>
                                </div>
                            </div>
                            
                            <!-- Group Actions (Stop Propagation to prevent toggle) -->
                            <div class="flex items-center gap-2" @click.stop>
                                <a :href="'/admin/master/skema/detail/kelompok/' + kelompok.id_kelompok_pekerjaan + '/edit'" 
                                   class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-full transition" title="Edit Kelompok">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                                <form :action="'/admin/master/skema/detail/kelompok/' + kelompok.id_kelompok_pekerjaan" method="POST" 
                                      @submit.prevent="confirmDeleteGroup($el)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-full transition" title="Hapus Kelompok">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Unit List (Expandable) -->
                        <div x-show="isExpanded(kelompok.id_kelompok_pekerjaan)" 
                             x-collapse
                             class="bg-white">
                             
                            <!-- Unit Header -->
                            <div class="grid grid-cols-12 gap-4 px-6 py-2 bg-gray-50/50 text-xs font-semibold uppercase text-gray-500 border-b border-gray-100">
                                <div class="col-span-2">Kode Unit</div>
                                <div class="col-span-9">Judul Unit</div>
                                <div class="col-span-1 text-center">Menu</div>
                            </div>

                            <!-- Units -->
                            <template x-for="(unit, index) in kelompok.unit_kompetensi" :key="unit.id_unit_kompetensi">
                                <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-gray-100 last:border-0 hover:bg-blue-50/30 transition items-center group">
                                    <div class="col-span-2 font-mono text-sm text-gray-600" x-text="unit.kode_unit"></div>
                                    <div class="col-span-9">
                                        <div class="font-medium text-gray-900" x-text="unit.judul_unit"></div>
                                    </div>
                                    <div class="col-span-1 flex justify-center relative" x-data="{ open: false }">
                                        <!-- Action Menu Button -->
                                        <button @click="open = !open" @click.outside="open = false" 
                                                class="text-gray-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50 focus:outline-none transition">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 top-8 w-48 bg-white rounded-lg shadow-xl border border-gray-100 z-10 py-1 origin-top-right">
                                            
                                            <a :href="'/admin/master/skema/detail/kelompok/' + kelompok.id_kelompok_pekerjaan + '/edit'" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                                                <i class="fas fa-edit w-5 text-center mr-2"></i> Edit Unit
                                            </a>
                                            
                                            <form :action="'/admin/master/skema/detail/unit/' + unit.id_unit_kompetensi + '/' + (index + 1)" method="POST" 
                                                  @submit.prevent="confirmDeleteUnit($el, unit.kode_unit)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-trash-alt w-5 text-center mr-2"></i> Hapus Unit
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty Unit State -->
                            <template x-if="kelompok.unit_kompetensi.length === 0">
                                <div class="px-6 py-8 text-center bg-gray-50/50">
                                    <p class="text-gray-500 italic mb-3">Belum ada unit kompetensi di kelompok ini.</p>
                                    <a :href="'/admin/master/skema/detail/kelompok/' + kelompok.id_kelompok_pekerjaan + '/edit'" 
                                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                                        <i class="fas fa-plus mr-2 text-xs"></i> Tambah Unit
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Empty Search State -->
                <template x-if="filteredKelompok.length === 0">
                    <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500 font-medium">Tidak ada data yang cocok dengan pencarian.</p>
                        <button @click="searchQuery = ''" class="mt-2 text-blue-600 hover:underline text-sm">Reset Pencarian</button>
                    </div>
                </template>
            </div>
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

        function kelompokHandler() {
            return {
                searchQuery: '',
                kelompokList: @json($skema->kelompokPekerjaan->load('unitKompetensi') ?? []),
                expandedGroups: [], 

                init() {
                    // Expand all by default if list is small
                    if (this.kelompokList.length > 0 && this.kelompokList.length <= 3) {
                         this.expandedGroups = this.kelompokList.map(g => g.id_kelompok_pekerjaan);
                    }
                },

                get filteredKelompok() {
                    if (this.searchQuery === '') {
                        return this.kelompokList;
                    }
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return this.kelompokList.filter(group => {
                        const groupMatch = group.nama_kelompok_pekerjaan.toLowerCase().includes(lowerQuery);
                        const unitMatch = group.unit_kompetensi.some(unit => 
                            unit.kode_unit.toLowerCase().includes(lowerQuery) || 
                            unit.judul_unit.toLowerCase().includes(lowerQuery)
                        );
                        return groupMatch || unitMatch;
                    });
                },

                get allExpanded() {
                     return this.kelompokList.length > 0 && this.expandedGroups.length === this.kelompokList.length;
                },

                isExpanded(id) {
                    return this.expandedGroups.includes(id);
                },

                toggleGroup(id) {
                    if (this.expandedGroups.includes(id)) {
                        this.expandedGroups = this.expandedGroups.filter(gId => gId !== id);
                    } else {
                        this.expandedGroups.push(id);
                    }
                },

                toggleAll() {
                    if (this.allExpanded) {
                        this.expandedGroups = [];
                    } else {
                        this.expandedGroups = this.kelompokList.map(g => g.id_kelompok_pekerjaan);
                    }
                },
                
                confirmDeleteGroup(formEl) {
                     if(confirm('Yakin ingin menghapus Kelompok ini? Semua Unit Kompetensi di dalamnya akan ikut terhapus.')) {
                         formEl.submit();
                     }
                },

                confirmDeleteUnit(formEl, code) {
                     if(confirm('Yakin ingin menghapus Unit ' + (code ? '('+code+')' : '') + '?')) {
                         formEl.submit();
                     }
                }
            }
        }
    </script>

</body>

</html>