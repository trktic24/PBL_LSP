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
          <div class="p-2 h-24 rounded-lg border text-gray-700 flex flex-col items-start justify-start transition hover:shadow-sm"
               :class="day.isToday ? 'border-blue-400 bg-blue-50 font-semibold' : 'border-gray-200 bg-white'">
            <span x-text="day.date" class="text-sm"></span>
            
            </div>
        </template>
      </template>

      <template x-if="viewMode === 'week'">
        <template x-for="(day, index) in weekDays()" :key="'week-' + index">
          <div class="p-2 h-96 rounded-lg border text-gray-700 flex flex-col items-start justify-start"
               :class="day.isToday ? 'border-blue-400 bg-blue-50 font-semibold' : 'border-gray-200 bg-white'">
            <div class="text-base text-gray-500 mb-2" x-text="day.date"></div>
             </div>
        </template>
      </template>
    </div>
</div>