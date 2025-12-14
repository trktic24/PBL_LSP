<div class="col-span-9 bg-white shadow-md rounded-xl border border-gray-200 p-6 relative">
          
    <div class="flex justify-between items-center mb-4">
      
      <div class="flex items-center gap-3">
          <h3 class="font-semibold text-lg" x-text="monthName + ' ' + year"></h3>
          
          <div x-show="viewMode === 'week'" x-transition class="relative" x-data="{ openWeekDropdown: false }">
              
              <button 
                  @click="openWeekDropdown = !openWeekDropdown" 
                  @click.away="openWeekDropdown = false"
                  class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold py-1.5 px-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50"
              >
                  <span x-text="'Week ' + selectedWeek"></span>
                  
                  <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                     :class="openWeekDropdown ? 'rotate-180' : ''"></i>
              </button>

              <div 
                  x-show="openWeekDropdown" 
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="opacity-0 scale-95"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-95"
                  class="absolute top-full left-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden"
                  style="display: none;"
              >
                  <template x-for="i in totalWeeksInMonth" :key="i">
                      <button 
                          @click="selectedWeek = i; weekDays(); openWeekDropdown = false"
                          class="block w-full text-left px-4 py-2 text-sm transition-colors"
                          :class="selectedWeek === i ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-700 hover:bg-blue-50 hover:text-gray-900'"
                      >
                          <span x-text="'Week ' + i"></span>
                      </button>
                  </template>
              </div>

          </div>
      </div>

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
          
          <div @click="openModal(day)"
               class="p-1 h-28 rounded-lg border text-gray-700 flex flex-col justify-between transition hover:shadow-md overflow-hidden relative group cursor-pointer"
               :class="day.isToday ? 'border-blue-400 bg-blue-50/30' : (day.isCurrentMonth ? 'border-gray-200 bg-white hover:border-blue-300' : 'border-transparent bg-gray-100 opacity-60')">
            
            <span x-text="day.date" class="text-xs font-semibold ml-1 mt-1 text-left block" :class="day.isToday ? 'text-blue-600' : 'text-gray-500'"></span>
            
            <div class="w-full flex flex-col px-1 mb-1">
                
                <template x-for="event in day.events.slice(0, 1)" :key="event.id_jadwal">
                    <div class="flex items-center w-full px-2 py-1.5 bg-white font-semibold border border-gray-200 rounded-full shadow-sm">
                       
                       <span class="w-3 h-3 rounded-full shrink-0 mr-2"
                             :class="{
                                 'bg-blue-500': event.Status_jadwal === 'Terjadwal',
                                 'bg-green-500': event.Status_jadwal === 'Selesai',
                                 'bg-red-500': event.Status_jadwal === 'Dibatalkan'
                             }">
                       </span>

                       <span class="text-[10px] font-semibold text-gray-700 truncate leading-tight" 
                             x-text="event.skema?.nama_skema || 'Jadwal'">
                       </span>
                    </div>
                </template>

                <template x-if="day.events.length > 1">
                    <div class="text-[10px] text-right text-gray-500 font-medium pr-1">
                        +<span x-text="day.events.length - 1"></span> More
                    </div>
                </template>

            </div>

          </div>
        </template>
      </template>

      <template x-if="viewMode === 'week'">
        <template x-for="(day, index) in weekDays()" :key="'week-' + index">
          
          <div @click="openModal(day)"
               class="p-1 h-28 rounded-lg border text-gray-700 flex flex-col justify-between transition overflow-hidden relative group"
               :class="{
                   'border-blue-400 bg-blue-50/30 cursor-pointer hover:shadow-md': day.isToday,
                   'border-gray-200 bg-white hover:border-blue-300 cursor-pointer hover:shadow-md': !day.isToday && day.isCurrentMonth,
                   'border-transparent bg-gray-100 opacity-60 cursor-default': !day.isCurrentMonth
               }">
            
            <div class="text-xs font-semibold ml-1 mt-1 text-left block" 
                 :class="day.isToday ? 'text-blue-600' : (day.isCurrentMonth ? 'text-gray-500' : 'text-gray-500')">
                <span x-text="day.date"></span>
            </div>
            
            <div class="w-full flex flex-col gap-1 px-1 mb-1" :class="!day.isCurrentMonth ? 'opacity-50' : ''">
                
                <template x-for="event in day.events.slice(0, 1)" :key="event.id_jadwal">
                    <div class="flex items-center w-full px-2 py-1.5 bg-white font-semibold border border-gray-200 rounded-full shadow-sm">
                       <span class="w-3 h-3 rounded-full shrink-0 mr-2"
                             :class="{
                                 'bg-blue-500': event.Status_jadwal === 'Terjadwal',
                                 'bg-green-500': event.Status_jadwal === 'Selesai',
                                 'bg-red-500': event.Status_jadwal === 'Dibatalkan'
                             }">
                       </span>
                       <span class="text-[10px] font-semibold text-gray-700 truncate leading-tight" 
                             x-text="event.skema?.nama_skema || 'Jadwal'">
                       </span>
                    </div>
                </template>

                <template x-if="day.events.length > 1">
                    <div class="text-[10px] text-right text-gray-500 font-medium pr-1">
                        +<span x-text="day.events.length - 1"></span> More
                    </div>
                </template>

            </div>
          </div>

        </template>
      </template>
    </div>

    <div x-show="isModalOpen" 
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/30 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
         
         <div class="bg-white w-full max-w-md m-4 rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[80vh]">
            
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Jadwal Tanggal <span x-text="selectedDateLabel"></span></h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-4 overflow-y-auto custom-scrollbar">
                <template x-if="selectedEvents.length === 0">
                    <p class="text-center text-gray-500 text-sm py-4">Tidak ada jadwal di tanggal ini.</p>
                </template>

                <div class="space-y-3">
                    <template x-for="event in selectedEvents" :key="event.id_jadwal">
                        <a :href="'/admin/master/jadwal/' + event.id_jadwal + '/daftar_hadir'"
                           class="block bg-white border border-gray-200 rounded-lg p-3 hover:border-blue-400 hover:shadow-md transition group">
                            
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold px-2 py-1 rounded-full"
                                    :class="{
                                        'bg-blue-100 text-blue-700': event.Status_jadwal === 'Terjadwal',
                                        'bg-green-100 text-green-700': event.Status_jadwal === 'Selesai',
                                        'bg-red-100 text-red-700': event.Status_jadwal === 'Dibatalkan'
                                    }" 
                                    x-text="event.Status_jadwal">
                                </span>
                                
                                {{-- [PERBAIKAN] Tampilkan Range Waktu --}}
                                <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded-md">
                                    <i class="far fa-clock mr-1"></i>
                                    <span x-text="formatTime(event.waktu_mulai)"></span> - <span x-text="formatTime(event.waktu_selesai)"></span>
                                </span>
                            </div>

                            <h4 class="font-semibold text-gray-900 text-sm mb-1 group-hover:text-blue-600 transition" x-text="event.skema?.nama_skema"></h4>
                            
                            <div class="flex items-center text-xs text-gray-500 gap-3">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                    <span x-text="event.master_tuk?.nama_lokasi || '-'"></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-1 text-gray-400"></i>
                                    <span x-text="(event.kuota_minimal || 0) + '/' + event.kuota_maksimal"></span>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 text-right">
                <button @click="isModalOpen = false" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Tutup</button>
            </div>
        </div>
    </div>

</div>