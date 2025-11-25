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
                                class="w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow"
                            >
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
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center transition-colors w-full sm:w-auto justify-center"
                        >
                            <i class="fas fa-filter mr-2"></i> Filter
                            <span x-show="filterStatus !== '' || filterJenisTuk !== ''" class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                        </button>

                        <div 
                            x-show="openFilter" 
                            @click.away="openFilter = false"
                            class="absolute right-0 top-full mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-lg z-50"
                            x-transition
                            style="display: none;"
                        >
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
                                            <i class="fas fa-caret-up" :class="sortCol === 'id_jadwal' && sortAsc ? 'text-gray-800' : ''"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'id_jadwal' && !sortAsc ? 'text-gray-800' : ''"></i>
                                        </div>
                                    </div>
                                </th>
                                <th @click="sortBy('tanggal_pelaksanaan')" class="sortable px-4 py-3 font-semibold w-32 group">
                                    <div class="flex items-center justify-between">
                                        Tanggal
                                        <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                            <i class="fas fa-caret-up" :class="sortCol === 'tanggal_pelaksanaan' && sortAsc ? 'text-gray-800' : ''"></i>
                                            <i class="fas fa-caret-down" :class="sortCol === 'tanggal_pelaksanaan' && !sortAsc ? 'text-gray-800' : ''"></i>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-4 py-3 font-semibold">Waktu</th>
                                <th @click="sortBy('skema.nama_skema')" class="sortable px-6 py-3 font-semibold">
                                    <div class="flex items-center justify-between">Nama Skema</div>
                                </th>
                                <th @click="sortBy('tuk.nama_lokasi')" class="sortable px-6 py-3 font-semibold">
                                    <div class="flex items-center justify-between">TUK</div>
                                </th>
                                <th class="px-6 py-3 font-semibold">Jenis TUK</th>
                                <th @click="sortBy('asesor.nama_lengkap')" class="sortable px-6 py-3 font-semibold">
                                    <div class="flex items-center justify-between">Asesor</div>
                                </th>
                                <th class="px-4 py-3 font-semibold text-center">Sesi</th>
                                <th class="px-4 py-3 font-semibold text-center">Kuota</th>
                                <th class="px-4 py-3 font-semibold text-center">Status</th>
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
                                            x-text="jadwal.Status_jadwal"
                                        ></span>
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
        
        // --- 2. MODAL STATE (Ini yang kemarin hilang) ---
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
        // LOGIKA KALENDER & MODAL
        // =====================================================================

        get monthName() { return dayjs().month(this.month).format('MMMM'); },

        // Helper: Ambil event untuk tanggal tertentu (Dipakai oleh Kalender)
        getEventsForDate(date) {
            if (!date) return [];
            const dateString = dayjs().year(this.year).month(this.month).date(date).format('YYYY-MM-DD');
            
            return this.allSchedules.filter(jadwal => {
                return dayjs(jadwal.tanggal_pelaksanaan).format('YYYY-MM-DD') === dateString;
            });
        },

        // Action: Buka Modal saat tanggal diklik
        openModal(day) {
            // Opsi: Hanya buka jika tanggal ada di bulan ini
            if (!day.isCurrentMonth) return;

            this.selectedEvents = day.events;
            this.selectedDateLabel = day.date + ' ' + this.monthName + ' ' + this.year;
            this.isModalOpen = true;
        },

        // =====================================================================
        // LOGIKA TABEL (FILTER + SORT + PAGINATION)
        // =====================================================================

        // 1. Filter Data
        get filteredData() {
            let data = this.allSchedules.filter(jadwal => {
                // A. Filter Wajib: Bulan & Tahun Kalender
                const jadwalDate = dayjs(jadwal.tanggal_pelaksanaan);
                const matchDate = jadwalDate.month() === this.month && jadwalDate.year() === this.year;
                if (!matchDate) return false;

                // B. Filter Status
                if (this.filterStatus !== '' && jadwal.Status_jadwal !== this.filterStatus) return false;

                // C. Filter Jenis TUK
                if (this.filterJenisTuk !== '' && jadwal.id_jenis_tuk != this.filterJenisTuk) return false;

                // D. Search Text
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

            // 2. Sorting
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

        // 3. Pagination Slice
        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + parseInt(this.pageSize);
            return this.filteredData.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.pageSize) || 1;
        },

        // =====================================================================
        // HELPERS & WATCHERS
        // =====================================================================

        getNestedValue(obj, path) {
            return path.split('.').reduce((o, p) => (o ? o[p] : null), obj);
        },

        // Actions Table
        sortBy(col) {
            if (this.sortCol === col) { this.sortAsc = !this.sortAsc; } else { this.sortCol = col; this.sortAsc = true; }
            this.currentPage = 1;
        },
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        goToPage(page) { if (page !== '...') this.currentPage = page; },

        // Pagination Dots Logic
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
        
        // Inisialisasi & Watchers
        init() {
            // Reset halaman tabel jika filter berubah
            this.$watch('pageSize', () => this.currentPage = 1);
            this.$watch('month', () => this.currentPage = 1);
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('filterStatus', () => this.currentPage = 1);
            this.$watch('filterJenisTuk', () => this.currentPage = 1);

            // Lock Scroll saat Modal Terbuka (Ini penting untuk UX Modal)
            this.$watch('isModalOpen', (value) => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        },

        // Formatters
        formatFullDate(dateStr) { return dayjs(dateStr).format('DD MMMM YYYY'); },
        formatTime(timeStr) { return timeStr ? timeStr.substring(0, 5) : '-'; },

        // =====================================================================
        // GENERATOR GRID KALENDER
        // =====================================================================
        
        toggleView(mode) { this.viewMode = mode; },
        weekDays() {
            const today = dayjs();
            const startOfWeek = today.startOf('week');
            const days = [];
            for (let i = 0; i < 7; i++) {
                const day = startOfWeek.add(i, 'day');
                days.push({ label: day.format('ddd'), date: day.format('D'), isToday: day.isSame(today, 'day') });
            }
            return days;
        },
        get miniDays() {
            const start = dayjs().year(this.year).month(this.month).startOf('month').day();
            const daysInMonth = dayjs().year(this.year).month(this.month).daysInMonth();
            const days = [];
            for (let i = 0; i < start; i++) days.push({ date: '', isCurrentMonth: false });
            for (let d = 1; d <= daysInMonth; d++) {
                const today = dayjs();
                days.push({ date: d, isCurrentMonth: true, isToday: today.date() === d && today.month() === this.month && today.year() === this.year });
            }
            return days;
        },
        
        get bigDays() {
            const start = dayjs().year(this.year).month(this.month).startOf('month').day();
            const daysInMonth = dayjs().year(this.year).month(this.month).daysInMonth();
            const days = [];
            const today = dayjs();

            // Slot kosong sebelum tanggal 1
            for (let i = 0; i < start; i++) {
                days.push({ date: '', isCurrentMonth: false, isToday: false, events: [] }); 
            }

            // Tanggal-tanggal bulan ini
            for (let d = 1; d <= daysInMonth; d++) {
                // Ambil events untuk tanggal ini (Agar muncul di kalender)
                const events = this.getEventsForDate(d);

                days.push({
                    date: d,
                    isCurrentMonth: true,
                    isToday: today.date() === d && today.month() === this.month && today.year() === this.year,
                    events: events // Data event masuk sini
                });
            }

            // Slot kosong setelah akhir bulan
            const totalSlots = start + daysInMonth;
            const slotsToFill = 42 - totalSlots;
            for (let i = 0; i < slotsToFill; i++) {
                days.push({ date: '', isCurrentMonth: false, isToday: false, events: [] });
            }

            return days;
        },
        
        nextMonth() { if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; } },
        prevMonth() { if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; } }
      };
    }
  </script>
</body>

</html>