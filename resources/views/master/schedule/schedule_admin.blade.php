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
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    [x-cloak] { display: none !important; }
    
    /* Cursor pointer untuk header sortable */
    th.sortable { cursor: pointer; user-select: none; }
    th.sortable:hover { background-color: #f3f4f6; }
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
              <template x-for="d in ['S','M','T','W','T','F','S']"><div x-text="d"></div></template>
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
              <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span><span class="font-medium text-gray-700 text-xs">Terjadwal</span>
              </div>
              <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                <span class="w-3 h-3 rounded-full bg-green-500"></span><span class="font-medium text-gray-700 text-xs">Selesai</span>
              </div>
              <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                <span class="w-3 h-3 rounded-full bg-red-500"></span><span class="font-medium text-gray-700 text-xs">Dibatalkan</span>
              </div>
            </div>
          </div>
        </div>

        <x-calendar-grid />
      </div>

      <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2"> 
              Daftar Jadwal Bulan <span x-text="monthName"></span>
          </h2>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-hidden">
          
          <div class="flex items-center space-x-2 mb-4">
              <label for="pageSize" class="text-sm text-gray-600">Show:</label>
              <select id="pageSize" x-model="pageSize" class="bg-white text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
              </select>
              <span class="text-sm text-gray-600">entries</span>
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
                          
                          <th @click="sortBy('tanggal_pelaksanaan')" class="sortable px-4 py-3 font-semibold group">
                              <div class="flex items-center justify-between">
                                  Tanggal Pelaksanaan
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'tanggal_pelaksanaan' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'tanggal_pelaksanaan' && !sortAsc ? 'text-gray-900' : 'text-gray-300''"></i>
                                  </div>
                              </div>
                          </th>
                          
                          <th @click="sortBy('waktu_mulai')" class="sortable px-4 py-3 font-semibold">
                              <div class="flex items-center justify-between">
                                  Waktu
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'waktu_mulai' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'waktu_mulai' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                  </div>
                              </div>
                          </th>

                          <th @click="sortBy('skema.nama_skema')" class="sortable px-6 py-3 font-semibold">
                              <div class="flex items-center justify-between">
                                  Nama Skema
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'skema.nama_skema' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'skema.nama_skema' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                  </div>
                              </div>
                          </th>

                          <th @click="sortBy('tuk.nama_lokasi')" class="sortable px-6 py-3 font-semibold">
                              <div class="flex items-center justify-between">
                                  TUK
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'tuk.nama_lokasi' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'tuk.nama_lokasi' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                  </div>
                              </div>
                          </th>
                          
                          <th class="px-6 py-3 font-semibold text-gray-500 cursor-default">Jenis TUK</th>
                          
                          <th @click="sortBy('asesor.nama_lengkap')" class="sortable px-6 py-3 font-semibold">
                              <div class="flex items-center justify-between">
                                  Asesor
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'asesor.nama_lengkap' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'asesor.nama_lengkap' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                  </div>
                              </div>
                          </th>
                          
                          <th @click="sortBy('sesi')" class="sortable px-4 py-3 font-semibold text-center">
                              <div class="flex items-center justify-center gap-1">
                                  Sesi
                                  <div class="flex flex-col -space-y-1 text-[10px] text-gray-400">
                                      <i class="fas fa-caret-up" :class="sortCol === 'sesi' && sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                      <i class="fas fa-caret-down" :class="sortCol === 'sesi' && !sortAsc ? 'text-gray-900' : 'text-gray-300'"></i>
                                  </div>
                              </div>
                          </th>
                          
                          <th @click="sortBy('kuota_maksimal')" class="sortable px-4 py-3 font-semibold text-center">
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
                                  <span class="px-2 py-1 text-[10px] font-medium rounded-full border"
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
                              Tidak ada jadwal di bulan ini.
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>

          <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
              <div class="text-sm text-gray-500 font-semibold">
                  Showing <span x-text="((currentPage - 1) * pageSize) + 1"></span>
                  - 
                  <span x-text="Math.min(currentPage * pageSize, filteredData.length)"></span>
                  of 
                  <span x-text="filteredData.length"></span> results
              </div>
              
              <div class="flex items-center space-x-1">
                   <button @click="prevPage" 
                           :disabled="currentPage === 1"
                           class="px-3 py-1 rounded-md border text-sm font-medium transition"
                           :class="currentPage === 1 ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-500 hover:bg-gray-50'">
                       <i class="fas fa-chevron-left"></i>
                   </button>

                   <template x-for="page in paginationRange" :key="page">
                       <button @click="goToPage(page)" 
                               class="px-3 py-1 rounded-md border text-sm font-medium transition"
                               :class="page === currentPage 
                                       ? 'bg-blue-600 border-blue-600 text-white' 
                                       : (page === '...' ? 'border-transparent text-gray-500 cursor-default' : 'border-gray-300 text-gray-500 hover:bg-gray-50')"
                               x-text="page"
                               :disabled="page === '...'">
                       </button>
                   </template>

                   <button @click="nextPage" 
                           :disabled="currentPage >= totalPages"
                           class="px-3 py-1 rounded-md border text-sm font-medium transition"
                           :class="currentPage >= totalPages ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-500 hover:bg-gray-50'">
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
        month: dayjs().month(), 
        year: dayjs().year(),   
        viewMode: 'month',
        
        // --- Sorting & Pagination State ---
        sortCol: 'tanggal_pelaksanaan',
        sortAsc: true,
        pageSize: 10,
        currentPage: 1,
        
        // Data dari Controller
        allSchedules: @json($schedules),

        get monthName() { return dayjs().month(this.month).format('MMMM'); },

        // 1. FILTER DATA (Berdasarkan Bulan)
        get filteredData() {
            let data = this.allSchedules.filter(jadwal => {
                const jadwalDate = dayjs(jadwal.tanggal_pelaksanaan);
                return jadwalDate.month() === this.month && jadwalDate.year() === this.year;
            });

            // 2. SORTING DATA
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

        // 3. PAGINATION DATA
        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + parseInt(this.pageSize);
            return this.filteredData.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.pageSize) || 1;
        },

        // Helper: Ambil data bersarang (cth: 'skema.nama_skema')
        getNestedValue(obj, path) {
            return path.split('.').reduce((o, p) => (o ? o[p] : null), obj);
        },

        // Action: Sort
        sortBy(col) {
            if (this.sortCol === col) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortCol = col;
                this.sortAsc = true;
            }
            this.currentPage = 1; // Reset ke halaman 1 saat sorting berubah
        },

        // Action: Pagination
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        // [BARU] Pindah ke halaman tertentu
        goToPage(page) {
            if (page !== '...') this.currentPage = page;
        },

        // [BARU] Logika untuk membuat array [1, 2, '...', 10]
        get paginationRange() {
            const total = this.totalPages;
            const current = this.currentPage;
            const delta = 2; // Jumlah halaman di kiri/kanan current page
            const range = [];
            const rangeWithDots = [];
            let l;

            for (let i = 1; i <= total; i++) {
                if (i == 1 || i == total || (i >= current - delta && i <= current + delta)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l !== 1) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            return rangeWithDots;
        },
        
        // Reset halaman saat pageSize berubah (via watcher logic di Alpine)
        init() {
            this.$watch('pageSize', () => this.currentPage = 1);
            this.$watch('month', () => this.currentPage = 1); // Reset saat ganti bulan
        },

        // Formatters
        formatFullDate(dateStr) { return dayjs(dateStr).format('DD MMMM YYYY'); },
        formatTime(timeStr) { return timeStr ? timeStr.substring(0, 5) : '-'; },

        // ... (Calendar Logic SAMA SEPERTI SEBELUMNYA) ...
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
            for (let i = 0; i < start; i++) days.push({ date: '', isCurrentMonth: false, isToday: false });
            for (let d = 1; d <= daysInMonth; d++) {
                days.push({ date: d, isCurrentMonth: true, isToday: today.date() === d && today.month() === this.month && today.year() === this.year });
            }
            const totalSlots = start + daysInMonth;
            const slotsToFill = 42 - totalSlots;
            for (let i = 0; i < slotsToFill; i++) days.push({ date: '', isCurrentMonth: false, isToday: false });
            return days;
        },
        nextMonth() {
            if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; }
        },
        prevMonth() {
            if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; }
        }
      };
    }
  </script>
</body>
</html>