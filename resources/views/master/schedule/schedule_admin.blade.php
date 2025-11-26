<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Schedule | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/dayjs.min.js"></script>

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

        /* Cursor pointer untuk header sortable */
        th.sortable {
            cursor: pointer;
            user-select: none;
        }

        th.sortable:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">

        <x-navbar />

        <main class="p-6" x-data="calendarApp()">

            <div class="flex justify-between items-end mb-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                    <h2 class="text-3xl font-semibold text-gray-900">Schedule Calendar</h2>
                </div>

            </div>

            <div class="grid grid-cols-12 gap-6 mb-8">
                <div class="col-span-3 space-y-6">
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 p-4">
                        <div class="flex justify-between items-center mb-6">
                            <button @click="prevMonth()" class="text-gray-600 hover:text-blue-600"><i class="fas fa-chevron-left"></i></button>
                            <h3 class="font-semibold text-gray-800" x-text="monthName + ' ' + year"></h3>
                            <button @click="nextMonth()" class="text-gray-600 hover:text-blue-600"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="grid grid-cols-7 text-center text-xs font-medium text-gray-500 mb-2 border-b border-gray-200 pb-2">
                            <template x-for="d in ['S','M','T','W','T','F','S']">
                                <div x-text="d"></div>
                            </template>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm">
                            <template x-for="(day, index) in miniDays" :key="index">
                                <div x-text="day.date"
                                    class="w-10 h-10 flex items-center justify-center rounded-full transition-all duration-200"
                                    :class="{
                        'bg-blue-600 text-white font-bold shadow-md': day.isToday && day.isCurrentMonth,
                        'text-gray-700 hover:bg-blue-50 cursor-pointer': !day.isToday && day.isCurrentMonth,
                        'text-gray-300': !day.isCurrentMonth
                     }">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-xl p-6 w-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Status</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-full border border-gray-300">
                                <span class="w-4 h-4 rounded-full bg-blue-600"></span><span class="font-medium text-gray-700 text-xs">Terjadwal</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-full border border-gray-300">
                                <span class="w-4 h-4 rounded-full bg-green-500"></span><span class="font-medium text-gray-700 text-xs">Selesai</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-3 py-2 rounded-full border border-gray-300">
                                <span class="w-4 h-4 rounded-full bg-red-500"></span><span class="font-medium text-gray-700 text-xs">Dibatalkan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <x-calendar-grid />
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-visible">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-list-ul text-blue-600"></i>
                        Daftar Jadwal Bulan <span x-text="monthName"></span>
                    </h3>
                </div>

                <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">

                        <div class="relative w-full sm:w-96">
                            <input
                                type="text"
                                x-model="search"
                                placeholder="Cari..."
                                class="w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>

                            <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                x-show="search.length > 0"
                                @click="search = ''"
                                x-cloak>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="flex items-center space-x-2">
                            <label for="pageSize" class="text-sm text-gray-600 whitespace-nowrap">Show:</label>
                            <select id="pageSize" x-model="pageSize" class="bg-white text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                        </div>
                    </div>

                    <div class="relative w-full lg:w-auto flex justify-end" x-data="{ openFilter: false }">
                        <button
                            @click="openFilter = !openFilter"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center transition-colors w-full sm:w-auto justify-center">
                            <i class="fas fa-filter mr-2"></i> Filter
                            <span x-show="filterStatus !== '' || filterJenisTuk !== ''" class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                        </button>

                        <div
                            x-show="openFilter"
                            @click.away="openFilter = false"
                            class="absolute right-0 top-full mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-lg z-50"
                            x-transition
                            style="display: none;">
                            <div class="p-2">
                                <div class="px-2 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</div>
                                <button @click="filterStatus = (filterStatus === 'Terjadwal' ? '' : 'Terjadwal')"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="filterStatus === 'Terjadwal' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                    Terjadwal
                                </button>
                                <button @click="filterStatus = (filterStatus === 'Selesai' ? '' : 'Selesai')"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="filterStatus === 'Selesai' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                    Selesai
                                </button>
                                <button @click="filterStatus = (filterStatus === 'Dibatalkan' ? '' : 'Dibatalkan')"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="filterStatus === 'Dibatalkan' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                    Dibatalkan
                                </button>
                            </div>

                            <div class="h-px bg-gray-100 mx-2"></div>

                            <div class="p-2">
                                <div class="px-2 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jenis TUK</div>
                                <button @click="filterJenisTuk = (filterJenisTuk === 1 ? '' : 1)"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="filterJenisTuk === 1 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                    Sewaktu
                                </button>
                                <button @click="filterJenisTuk = (filterJenisTuk === 2 ? '' : 2)"
                                    class="w-full text-left px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="filterJenisTuk === 2 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                                    Tempat Kerja
                                </button>
                            </div>

                            <div x-show="filterStatus !== '' || filterJenisTuk !== ''" class="p-2 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                                <button @click="filterStatus = ''; filterJenisTuk = '';" class="w-full text-center text-xs text-red-600 font-medium hover:underline">
                                    Hapus Semua Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left border border-gray-200">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr class="divide-x divide-gray-200 border-b border-gray-200">

                                <th @click="sortBy('id_jadwal')" class="sortable px-4 py-3 font-semibold w-12 text-center group">
                                    <div class="flex items-center justify-center gap-1">
                                        ID
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'id_jadwal' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'id_jadwal' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('tanggal_pelaksanaan')" class="sortable px-4 py-3 font-semibold w-32 group">
                                    <div class="flex items-center justify-between">
                                        Tanggal Pelaksanaan
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'tanggal_pelaksanaan' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'tanggal_pelaksanaan' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('waktu_mulai')" class="sortable px-4 py-3 font-semibold w-24 group">
                                    <div class="flex items-center justify-between">
                                        Waktu
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'waktu_mulai' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'waktu_mulai' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('skema.nama_skema')" class="sortable px-6 py-3 font-semibold group">
                                    <div class="flex items-center justify-between">
                                        Nama Skema
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'skema.nama_skema' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'skema.nama_skema' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('tuk.nama_lokasi')" class="sortable px-6 py-3 font-semibold group">
                                    <div class="flex items-center justify-between">
                                        TUK
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'tuk.nama_lokasi' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'tuk.nama_lokasi' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th class="px-6 py-3 font-semibold text-gray-500 cursor-default">Jenis TUK</th>

                                <th @click="sortBy('asesor.nama_lengkap')" class="sortable px-6 py-3 font-semibold group">
                                    <div class="flex items-center justify-between">
                                        Asesor
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'asesor.nama_lengkap' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'asesor.nama_lengkap' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('sesi')" class="sortable px-4 py-3 font-semibold text-center group">
                                    <div class="flex items-center justify-center gap-1">
                                        Sesi
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'sesi' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'sesi' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th @click="sortBy('kuota_maksimal')" class="sortable px-4 py-3 font-semibold text-center group">
                                    <div class="flex items-center justify-center gap-1">
                                        Kuota
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'kuota_maksimal' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'kuota_maksimal' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                        </div>
                                    </div>
                                </th>

                                <th class="px-4 py-3 font-semibold text-center text-gray-500 cursor-default">Status</th>

                                <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <template x-for="jadwal in paginatedData" :key="jadwal.id_jadwal">
                                <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                                    <td class="px-4 py-4 text-center font-medium text-gray-500" x-text="jadwal.id_jadwal"></td>
                                    <td class="px-4 py-4 font-bold text-gray-700 whitespace-nowrap" x-text="formatFullDate(jadwal.tanggal_pelaksanaan)"></td>
                                    <td class="px-4 py-4 font-medium" x-text="formatTime(jadwal.waktu_mulai)"></td>
                                    <td class="px-6 py-4 font-medium text-gray-900" x-text="jadwal.skema?.nama_skema || '-'"></td>
                                    <td class="px-6 py-4" x-text="jadwal.tuk?.nama_lokasi || '-'"></td>
                                    <td class="px-6 py-4" x-text="jadwal.jenis_tuk?.jenis_tuk || '-'"></td>
                                    <td class="px-6 py-4" x-text="jadwal.asesor?.nama_lengkap || '-'"></td>
                                    <td class="px-4 py-4 text-center font-bold" x-text="jadwal.sesi"></td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <span x-text="jadwal.kuota_minimal + ' / ' + jadwal.kuota_maksimal"></span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-[10px] font-medium rounded-full border"
                                            :class="{
                                                'bg-blue-100 text-blue-700 border-blue-200': jadwal.Status_jadwal === 'Terjadwal',
                                                'bg-green-100 text-green-700 border-green-200': jadwal.Status_jadwal === 'Selesai',
                                                'bg-red-100 text-red-700 border-red-200': jadwal.Status_jadwal === 'Dibatalkan'
                                            }"
                                            x-text="jadwal.Status_jadwal"></span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a :href="'/master/schedule/attendance/' + jadwal.id_jadwal"
                                            class="flex items-center justify-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition">
                                            <i class="fas fa-eye"></i> <span>Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="paginatedData.length === 0">
                                <td colspan="12" class="px-6 py-8 text-center text-gray-400 italic">
                                    Tidak ada jadwal yang cocok dengan filter ini di bulan ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200" x-show="filteredData.length > 0">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium" x-text="((currentPage - 1) * pageSize) + 1"></span>
                        to
                        <span class="font-medium" x-text="Math.min(currentPage * pageSize, filteredData.length)"></span>
                        of
                        <span class="font-medium" x-text="filteredData.length"></span> results
                    </div>

                    <div class="flex items-center space-x-1">
                        <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 rounded-md border text-sm font-medium transition" :class="currentPage === 1 ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-500 hover:bg-gray-50'">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <template x-for="page in paginationRange" :key="page">
                            <button @click="goToPage(page)" class="px-3 py-1 rounded-md border text-sm font-medium transition" :class="page === currentPage ? 'bg-blue-600 border-blue-600 text-white' : (page === '...' ? 'border-transparent text-gray-500 cursor-default' : 'border-gray-300 text-gray-500 hover:bg-gray-50')" x-text="page" :disabled="page === '...'"></button>
                        </template>

                        <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded-md border text-sm font-medium transition" :class="currentPage >= totalPages ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-500 hover:bg-gray-50'">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        function calendarApp() {
            return {
                // --- 1. CALENDAR STATE ---
                month: dayjs().month(),
                year: dayjs().year(),
                viewMode: 'month',
                selectedWeek: 1, // Default

                // --- 2. MODAL STATE ---
                isModalOpen: false,
                selectedEvents: [],
                selectedDateLabel: '',

                // --- 3. FILTER STATE ---
                search: '',
                filterStatus: '',
                filterJenisTuk: '',

                // --- 4. PAGINATION & SORT STATE ---
                sortCol: 'tanggal_pelaksanaan',
                sortAsc: true,
                pageSize: 10,
                currentPage: 1,

                // --- 5. DATA SUMBER ---
                allSchedules: @json($schedules),

                // =====================================================================
                // LOGIKA MINGGU OTOMATIS (AUTO DETECT WEEK)
                // =====================================================================

                // [PERBAIKAN UTAMA] Hitung minggu berdasarkan posisi grid visual
                setCurrentWeek() {
                    const today = dayjs();

                    // Cek apakah Kalender sedang membuka Bulan & Tahun saat ini (Hari Ini)
                    if (today.month() === this.month && today.year() === this.year) {
                        const date = today.date(); // Tanggal hari ini (misal: 25)
                        const startOfMonth = dayjs().year(this.year).month(this.month).startOf('month');
                        const firstDayIndex = startOfMonth.day(); // Hari apa tgl 1 itu (0=Minggu, 6=Sabtu)

                        // Rumus: (Tanggal + Index Hari Pertama) / 7, dibulatkan ke atas
                        // Ini mencerminkan posisi baris di kalender visual
                        this.selectedWeek = Math.ceil((date + firstDayIndex) / 7);
                    } else {
                        // Jika membuka bulan lain (masa depan/lalu), default ke Minggu 1
                        this.selectedWeek = 1;
                    }
                },

                // Hitung total minggu dalam bulan ini (untuk Loop Dropdown)
                get totalWeeksInMonth() {
                    const firstDayOfMonth = dayjs().year(this.year).month(this.month).startOf('month');
                    const lastDayOfMonth = dayjs().year(this.year).month(this.month).endOf('month');
                    const used = firstDayOfMonth.day() + lastDayOfMonth.date();
                    return Math.ceil(used / 7);
                },

                // =====================================================================
                // NAVIGASI KALENDER (UPDATED)
                // =====================================================================

                toggleView(mode) {
                    this.viewMode = mode;
                    // Saat pindah ke mode week, hitung ulang minggu saat ini
                    if (mode === 'week') {
                        this.setCurrentWeek();
                    }
                },

                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                    this.setCurrentWeek(); // [UPDATE] Cek ulang apakah bulan baru ini bulan "hari ini"
                },

                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                    this.setCurrentWeek(); // [UPDATE] Cek ulang apakah bulan baru ini bulan "hari ini"
                },

                // =====================================================================
                // INITIALISASI
                // =====================================================================

                init() {
                    this.setCurrentWeek(); // [PENTING] Jalankan saat load pertama kali

                    this.$watch('pageSize', () => this.currentPage = 1);
                    this.$watch('month', () => this.currentPage = 1);
                    this.$watch('search', () => this.currentPage = 1);
                    this.$watch('filterStatus', () => this.currentPage = 1);
                    this.$watch('filterJenisTuk', () => this.currentPage = 1);
                    this.$watch('isModalOpen', (value) => {
                        if (value) {
                            document.body.classList.add('overflow-hidden');
                        } else {
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                },

                // =====================================================================
                // SISA LOGIKA (FILTER, SORT, RENDER GRID) - TIDAK BERUBAH
                // =====================================================================

                get monthName() {
                    return dayjs().month(this.month).format('MMMM');
                },

                getEventsForDate(date) {
                    if (!date) return [];
                    const dateString = dayjs().year(this.year).month(this.month).date(date).format('YYYY-MM-DD');
                    return this.allSchedules.filter(jadwal => dayjs(jadwal.tanggal_pelaksanaan).format('YYYY-MM-DD') === dateString);
                },

                getEventsForFullDate(dateObj) {
                    const dateString = dateObj.format('YYYY-MM-DD');
                    return this.allSchedules.filter(jadwal => dayjs(jadwal.tanggal_pelaksanaan).format('YYYY-MM-DD') === dateString);
                },

                openModal(day) {
                    if (!day.isCurrentMonth) return;
                    this.selectedEvents = day.events;
                    this.selectedDateLabel = day.date + ' ' + this.monthName + ' ' + this.year;
                    this.isModalOpen = true;
                },

                get filteredData() {
                    let data = this.allSchedules.filter(jadwal => {
                        const jadwalDate = dayjs(jadwal.tanggal_pelaksanaan);
                        const matchDate = jadwalDate.month() === this.month && jadwalDate.year() === this.year;
                        if (!matchDate) return false;
                        if (this.filterStatus !== '' && jadwal.Status_jadwal !== this.filterStatus) return false;
                        if (this.filterJenisTuk !== '' && jadwal.id_jenis_tuk != this.filterJenisTuk) return false;
                        if (this.search !== '') {
                            const q = this.search.toLowerCase();
                            const match = (
                                (String(jadwal.id_jadwal)).toLowerCase().includes(q) ||
                                (String(jadwal.sesi)).toLowerCase().includes(q) ||
                                (jadwal.skema?.nama_skema || '').toLowerCase().includes(q) ||
                                (jadwal.tuk?.nama_lokasi || '').toLowerCase().includes(q) ||
                                (jadwal.asesor?.nama_lengkap || '').toLowerCase().includes(q)
                            );
                            if (!match) return false;
                        }
                        return true;
                    });
                    return data.sort((a, b) => {
                        let valA = this.getNestedValue(a, this.sortCol);
                        let valB = this.getNestedValue(b, this.sortCol);
                        if (valA === null) valA = "";
                        if (valB === null) valB = "";
                        if (typeof valA === 'string') valA = valA.toLowerCase();
                        if (typeof valB === 'string') valB = valB.toLowerCase();
                        if (valA < valB) return this.sortAsc ? -1 : 1;
                        if (valA > valB) return this.sortAsc ? 1 : -1;
                        return 0;
                    });
                },

                get paginatedData() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    const end = start + parseInt(this.pageSize);
                    return this.filteredData.slice(start, end);
                },
                get totalPages() {
                    return Math.ceil(this.filteredData.length / this.pageSize) || 1;
                },
                getNestedValue(obj, path) {
                    return path.split('.').reduce((o, p) => (o ? o[p] : null), obj);
                },
                sortBy(col) {
                    if (this.sortCol === col) {
                        this.sortAsc = !this.sortAsc;
                    } else {
                        this.sortCol = col;
                        this.sortAsc = true;
                    }
                    this.currentPage = 1;
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                goToPage(page) {
                    if (page !== '...') this.currentPage = page;
                },

                get paginationRange() {
                    const total = this.totalPages;
                    const current = this.currentPage;
                    const delta = 2;
                    const range = [];
                    const rangeWithDots = [];
                    let l;
                    for (let i = 1; i <= total; i++) {
                        if (i == 1 || i == total || (i >= current - delta && i <= current + delta)) range.push(i);
                    }
                    for (let i of range) {
                        if (l) {
                            if (i - l === 2) rangeWithDots.push(l + 1);
                            else if (i - l !== 1) rangeWithDots.push('...');
                        }
                        rangeWithDots.push(i);
                        l = i;
                    }
                    return rangeWithDots;
                },

                formatFullDate(dateStr) {
                    return dayjs(dateStr).format('DD MMMM YYYY');
                },
                formatTime(timeStr) {
                    return timeStr ? timeStr.substring(0, 5) : '-';
                },

                weekDays() {
                    const startOfMonth = dayjs().year(this.year).month(this.month).startOf('month');
                    const startOfFirstWeek = startOfMonth.startOf('week');
                    const startOfSelectedWeek = startOfFirstWeek.add(this.selectedWeek - 1, 'week');

                    const days = [];
                    for (let i = 0; i < 7; i++) {
                        const day = startOfSelectedWeek.add(i, 'day');
                        const events = this.getEventsForFullDate(day); // Pakai helper khusus week view
                        days.push({
                            label: day.format('ddd'),
                            date: day.format('D'),
                            isToday: day.isSame(dayjs(), 'day'),
                            isCurrentMonth: day.month() === this.month,
                            events: events
                        });
                    }
                    return days;
                },
                get miniDays() {
                    const start = dayjs().year(this.year).month(this.month).startOf('month').day();
                    const daysInMonth = dayjs().year(this.year).month(this.month).daysInMonth();
                    const days = [];
                    for (let i = 0; i < start; i++) days.push({
                        date: '',
                        isCurrentMonth: false
                    });
                    for (let d = 1; d <= daysInMonth; d++) {
                        const today = dayjs();
                        days.push({
                            date: d,
                            isCurrentMonth: true,
                            isToday: today.date() === d && today.month() === this.month && today.year() === this.year
                        });
                    }
                    return days;
                },

                get bigDays() {
                    const startOfMonth = dayjs().year(this.year).month(this.month).startOf('month');
                    const startOfGrid = startOfMonth.startOf('week'); // Hari Minggu sebelum tanggal 1 (atau tgl 1 itu sendiri)
                    const days = [];

                    // Kita buat grid fix 6 minggu (42 hari) agar kalender stabil tidak naik turun
                    for (let i = 0; i < 42; i++) {
                        const day = startOfGrid.add(i, 'day');
                        const isCurrentMonth = day.month() === this.month;

                        // Ambil event
                        const events = this.getEventsForFullDate(day);

                        days.push({
                            date: day.format('D'), // [FIX] Selalu isi angka tanggal (misal 30, 31, 1, 2...)
                            isCurrentMonth: isCurrentMonth,
                            isToday: day.isSame(dayjs(), 'day'),
                            events: events
                        });
                    }

                    return days;
                },

                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                },
                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                }
            };
        }
    </script>
</body>

</html>