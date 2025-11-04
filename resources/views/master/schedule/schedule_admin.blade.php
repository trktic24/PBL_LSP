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
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    <main class="p-6" x-data="calendarApp()">
      
      <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
      <h2 class="text-3xl font-bold text-gray-900 mb-6">Schedule</h2>

      <div class="grid grid-cols-12 gap-6">
        
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
                     class="w-10 h-10 flex items-center justify-center rounded-full"
                     :class="{
                        'bg-white border-2 border-blue-600 text-blue-600 font-semibold': day.isToday && day.isCurrentMonth,
                        'text-gray-800 hover:bg-gray-100 transition-colors cursor-pointer': !day.isToday && day.isCurrentMonth,
                        'text-gray-300': !day.isCurrentMonth
                     }">
                </div>
              </template>
            </div>
          </div>

          <div class="bg-white shadow-[4px_4px_8px_rgba(0,0,0,0.1),_-4px_-4px_8px_rgba(255,255,255,0.9)] rounded-xl p-6 w-full max-w-md mx-auto">
            <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-2">
              <h3 class="text-lg font-semibold text-gray-800">Status</h3>
              <button class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-plus text-lg"></i>
              </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div class="flex items-center space-x-2 bg-white px-2 py-2 rounded-full shadow-[2px_2px_6px_rgba(0,0,0,0.15),_-2px_-2px_6px_rgba(255,255,255,0.9)]">
                <span class="w-5 h-5 rounded-full bg-blue-600"></span>
                <span class="font-semibold text-gray-800 text-xs">Confirm</span>
              </div>
              <div class="flex items-center space-x-2 bg-white px-2 py-2 rounded-full shadow-[2px_2px_6px_rgba(0,0,0,0.15),_-2px_-2px_6px_rgba(255,255,255,0.9)]">
                <span class="w-5 h-5 rounded-full bg-yellow-400"></span>
                <span class="font-semibold text-gray-800 text-xs">Pending</span>
              </div>
              <div class="flex items-center space-x-2 bg-white px-2 py-2 rounded-full shadow-[2px_2px_6px_rgba(0,0,0,0.15),_-2px_-2px_6px_rgba(255,255,255,0.9)]">
                <span class="w-5 h-5 rounded-full bg-orange-400"></span>
                <span class="font-semibold text-gray-800 text-xs">Reschedule</span>
              </div>
              <div class="flex items-center space-x-2 bg-white px-2 py-2 rounded-full shadow-[2px_2px_6px_rgba(0,0,0,0.15),_-2px_-2px_6px_rgba(255,255,255,0.9)]">
                <span class="w-5 h-5 rounded-full bg-red-400"></span>
                <span class="font-semibold text-gray-800 text-xs">Cancel</span>
              </div>
            </div>
          </div>

        </div>
        <div class="col-span-9 bg-white shadow-md rounded-xl border border-gray-200 p-6">
          
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-lg" x-text="monthName + ' ' + year"></h3>
            <div class="flex space-x-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
              <button @click="toggleView('week')"
                      :class="viewMode === 'week' 
                               ? 'px-4 py-2 text-gray-800 font-semibold rounded-xl text-sm bg-gradient-to-r from-[#b4e1ff] to-[#d7f89c] shadow-md' 
                               : 'px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm transition-all'">
                Week
              </button>
              <button @click="toggleView('month')"
                      :class="viewMode === 'month' 
                               ? 'px-4 py-2 text-gray-800 font-semibold rounded-xl text-sm bg-gradient-to-r from-[#b4e1ff] to-[#d7f89c] shadow-md' 
                               : 'px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm transition-all'">
                Month
              </button>
            </div>
          </div>

          <div class="grid grid-cols-7 text-center text-gray-700 font-medium border-b pb-2 mb-2">
            <template x-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']"><div x-text="d"></div></template>
          </div>

          <div class="grid grid-cols-7 gap-2 text-center text-base">
            <template x-if="viewMode === 'month'">
              <template x-for="(day, index) in bigDays" :key="index">
                <div class="p-8 rounded-lg border text-gray-700"
                     :class="day.isToday ? 'border-blue-400 bg-blue-50 font-semibold' : 'border-gray-200 bg-white'">
                  <span x-text="day.date"></span>
                </div>
              </template>
            </template>

            <template x-if="viewMode === 'week'">
              <template x-for="(day, index) in weekDays()" :key="'week-' + index">
                <div class="p-8 rounded-lg border text-gray-700"
                     :class="day.isToday ? 'border-blue-400 bg-blue-50 font-semibold' : 'border-gray-200 bg-white'">
                  <div class="text-base text-gray-500" x-text="day.date"></div>
                </div>
              </template>
            </template>
          </div>
        </div>
        </div>
      <div class="mt-10">
        
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
          <div class="relative w-full md:w-1/3">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..."
                   class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
          </div>

          <div class="flex items-center space-x-3 ml-auto" x-data="{ open: false }">
            <div class="relative">
              <button @click="open = !open"
                      class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
                <i class="fas fa-filter mr-2 text-base"></i> Filter
                <i class="fas fa-caret-down ml-2"></i>
              </button>
              <div x-show="open" @click.away="open = false"
                  class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20"
                  x-transition>
                <a href="#"
                  class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600"
                  @click.prevent="open = false; console.log('Filter: Nama Skema')">
                  Nama Skema
                </a>
                <a href="#"
                  class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600"
                  @click.prevent="open = false; console.log('Filter: Asesmen yang Sudah Selesai')">
                  Asesmen yang Sudah Selesai
                </a>
              </div>
            </div>
            <a href="{{ url('add_schedule') }}"
             class="px-4 py-2 bg-blue-600 hover:bg-blue-800 text-white rounded-lg text-sm font-medium flex items-center shadow-md">
              <i class="fas fa-calendar-plus mr-2 text-base"></i> Add Schedule
            </a>  
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
          <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
              <tr>
                <th class="px-6 py-3 text-left">Id</th>
                <th class="px-6 py-3 text-left">Tanggal Pelaksanaan</th>
                <th class="px-6 py-3 text-left">Kode Unit Skema</th>
                <th class="px-6 py-3 text-left">Nama Skema</th>
                <th class="px-6 py-3 text-left">Jenis TUK</th>
                <th class="px-6 py-3 text-left">Gelombang</th>
                <th class="px-6 py-3 text-left">Daftar Asesor</th>
                <th class="px-6 py-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4">15/12/2025</td>
                <td class="px-6 py-4">2609051226</td>
                <td class="px-6 py-4 font-medium text-gray-800">Cybersecurity</td>
                <td class="px-6 py-4">Mandiri, Sewaktu, dan Tempat Kerja</td>
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4 leading-5">Roihan Enrico<br>Rafa Saputra<br>Zulfikar Pujangga</td>
                <td class="px-6 py-4">
                  <a href="{{ route('master_schedule') }}"
                     class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg shadow transition-all">
                    <i class="fas fa-arrow-up-right-from-square text-base"></i>
                    <span>Detail</span>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      </main>
    </div>

  <script>
    function calendarApp() {
      return {
        month: dayjs().month(), // bulan sekarang (0 = Januari)
        year: dayjs().year(),   // tahun sekarang
        viewMode: 'month',      // mode tampilan awal
        
        // === Nama Bulan Dinamis ===
        get monthName() {
          return dayjs().month(this.month).format('MMMM');
        },

        // === Ganti Mode (Week / Month) ===
        toggleView(mode) {
          this.viewMode = mode;
        },

        // === Hari Mingguan (Week View) ===
        weekDays() {
          const today = dayjs();
          const startOfWeek = today.startOf('week');
          const days = [];
          for (let i = 0; i < 7; i++) {
            const day = startOfWeek.add(i, 'day');
            days.push({
              label: day.format('ddd'),
              date: day.format('D'),
              isToday: day.isSame(today, 'day')
            });
          }
          return days;
        },

        // === Kalender Kecil di Sisi Kiri ===
        get miniDays() {
          const start = dayjs().year(this.year).month(this.month).startOf('month').day();
          const daysInMonth = dayjs().year(this.year).month(this.month).daysInMonth();
          const days = [];
          
          // Slot kosong di awal
          for (let i = 0; i < start; i++) {
            days.push({ date: '', isCurrentMonth: false });
          }
          
          // Tanggal di bulan ini
          for (let d = 1; d <= daysInMonth; d++) {
            const today = dayjs();
            days.push({
              date: d,
              isCurrentMonth: true,
              isToday:
                today.date() === d &&
                today.month() === this.month &&
                today.year() === this.year
            });
          }
          return days;
        },

        // === Kalender Besar (Month View) ===
        get bigDays() {
          const start = dayjs().year(this.year).month(this.month).startOf('month').day();
          const daysInMonth = dayjs().year(this.year).month(this.month).daysInMonth();
          const days = [];
          const today = dayjs();

          // Slot kosong sebelum tanggal 1
          for (let i = 0; i < start; i++) {
            days.push({ date: '', isCurrentMonth: false, isToday: false }); 
          }

          // Isi semua tanggal
          for (let d = 1; d <= daysInMonth; d++) {
            days.push({
              date: d,
              isCurrentMonth: true,
              isToday:
                today.date() === d &&
                today.month() === this.month &&
                today.year() === this.year
            });
          }

          // Slot kosong setelah tanggal terakhir (untuk melengkapi grid)
          const totalSlots = start + daysInMonth;
          const slotsToFill = 42 - totalSlots; // 42 slot = 6 baris x 7 hari
          for (let i = 0; i < slotsToFill; i++) {
            days.push({ date: '', isCurrentMonth: false, isToday: false });
          }

          return days;
        },

        // === Navigasi Bulan Berikutnya ===
        nextMonth() {
          if (this.month === 11) {
            this.month = 0;
            this.year++;
          } else {
            this.month++;
          }
        },

        // === Navigasi Bulan Sebelumnya ===
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