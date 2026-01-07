<?php $__env->startSection('title', 'Struktur Organisasi'); ?>

<?php $__env->startSection('content'); ?>
<section id="struktur" class="pt-24 pb-0 -mb-40 bg-white min-h-screen">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">Struktur Organisasi</h1>

        
        <?php
            $struktur = \App\Models\StrukturOrganisasi::orderBy('urutan', 'asc')->get();
        ?>
        
        
        <script>
            window.dbStruktur = JSON.parse('<?php echo addslashes(json_encode($struktur)); ?>');
        </script>


        <div class="w-full overflow-x-auto pb-12 mt-10">
            <div class="min-w-[1024px] flex flex-col items-center">
            
            
            <div class="relative z-30">
                <div onclick="openByUrutan([1])" 
                     class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-4 px-4 md:px-10 w-80 shadow-sm mx-auto relative z-30 transform hover:scale-105">
                    DEWAN PENGARAH
                </div>
            </div>

            
            <div class="h-10 w-[2px] bg-black relative z-0"></div>

            
            <div class="relative w-full block h-32 mb-0 z-40">
                
                
                <div class="z-50 absolute left-1/2 -translate-x-1/2 top-0">
                    <div onclick="openByUrutan([2])" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-4 px-4 md:px-10 w-80 shadow-sm mx-auto relative z-50 transform hover:scale-105">
                        KETUA LSP
                    </div>
                </div>

                
                <div class="absolute top-1/2 left-1/2 w-80 h-[2px] border-t-[2px] border-dashed border-black pointer-events-none z-0"></div> 

                
                <div class="z-[100] absolute top-1/2 -translate-y-1/2 left-1/2 ml-80">
                     <div onclick="openByUrutan([7, 8])" 
                          class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-4 w-56 text-sm shadow-sm mx-auto flex flex-col items-center justify-center h-24 relative z-[100] transform hover:scale-105">
                        <span>KOMITE SKEMA</span>
                        <span class="text-xs font-normal mt-1 text-gray-200">(Ketua & Anggota)</span>
                    </div>
                </div>

                
                <div class="h-full w-[2px] bg-black absolute left-1/2 -translate-x-1/2 top-1/2 pointer-events-none z-0"></div>
            </div>

            <div class="h-8 w-[2px] bg-black relative z-0"></div>


            
            
            
            <div class="relative w-[968px] h-8 z-0 pointer-events-none">
                <div class="absolute top-0 border-t-[2px] border-black left-[112px] right-[112px]"></div>
                <div class="absolute top-0 left-[112px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 left-[360px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 right-[360px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 right-[112px] h-8 w-[2px] bg-black"></div>
            </div>

            
            <div class="flex justify-center gap-6 w-auto relative z-30">
                
                
                <div class="flex flex-col items-center relative z-30">
                    <div onclick="openByUrutan([10, 11])" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30 transform hover:scale-105">
                        <span class="text-center">BIDANG<br>MANAJEMEN MUTU</span>
                    </div>
                </div>

                
                <div class="flex flex-col items-center relative z-30">
                    <div onclick="openByUrutan([5, 6])" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30 transform hover:scale-105">
                        <span class="text-center">BIDANG<br>SERTIFIKASI</span>
                    </div>
                </div>

                
                <div class="flex flex-col items-center relative z-30">
                    <div onclick="openByUrutan([9])" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-56 h-24 flex items-center justify-center shadow-sm relative z-30 transform hover:scale-105">
                        <span class="text-center">BIDANG<br>KERJASAMA</span>
                    </div>
                </div>

                
                <div class="flex flex-col items-center relative z-30">
                    <div onclick="openByUrutan([3, 4])" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30 transform hover:scale-105">
                        <span class="text-center">BIDANG<br>ADMINISTRASI</span>
                    </div>
                </div>

            </div>
            </div>
        </div>
    </div>

    
    <div id="orgModal" class="fixed inset-0 z-[200] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/70 transition-opacity backdrop-blur-sm" onclick="closeOrgModal()"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md scale-100 opacity-100 ring-1 ring-black/5">
                
                <div class="bg-gradient-to-br from-blue-700 to-blue-900 px-4 py-8 sm:px-8 text-center relative overflow-hidden">
                    
                    
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>

                    
                    <button onclick="closeOrgModal()" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/10 hover:bg-black/20 rounded-full p-2 transition-all z-20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    
                    <button id="prevPersonBtn" onclick="prevPerson()" class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full p-2 hidden z-20 transition-colors backdrop-blur-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="nextPersonBtn" onclick="nextPerson()" class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white rounded-full p-2 hidden z-20 transition-colors backdrop-blur-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    
                    <div class="mx-auto h-28 w-28 rounded-full border-[6px] border-white/20 shadow-xl overflow-hidden bg-white relative z-10 ring-2 ring-white/50">
                        <img id="modal-img" src="" alt="Foto Profil" class="h-full w-full object-cover">
                    </div>

                    
                    <h3 class="mt-5 text-2xl font-bold text-white tracking-tight leading-tight" id="modal-nama">Nama Pejabat</h3>
                    <div class="mt-2 inline-block px-3 py-1 rounded-full bg-blue-500/30 border border-blue-400/30 backdrop-blur-md">
                        <p class="text-blue-50 text-xs font-bold uppercase tracking-widest" id="modal-jabatan">JABATAN</p>
                    </div>

                    
                    <div id="slide-indicators" class="flex justify-center gap-2 mt-6 hidden">
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let currentProfiles = [];
    let currentIndex = 0;

    // Fungsi Utama: Membuka Modal berdasarkan Nomor Urut (Bisa Array)
    // Contoh: openByUrutan([10, 11]) akan mengambil semua orang dgn urutan 10 dan 11
    function openByUrutan(arrayUrutan) {
        // Filter data dari variabel global window.dbStruktur
        // Cari orang yang 'urutan'-nya ada di dalam arrayUrutan
        currentProfiles = window.dbStruktur.filter(item => arrayUrutan.includes(item.urutan));
        
        // Sorting agar yang urutannya lebih kecil muncul duluan
        currentProfiles.sort((a, b) => a.urutan - b.urutan);

        // Jika data kosong, buat placeholder dummy agar tidak error
        if (currentProfiles.length === 0) {
            currentProfiles = [{
                nama: 'Belum Terisi',
                jabatan: 'Posisi Kosong',
                gambar: null
            }];
        }

        currentIndex = 0;
        updateModalDisplay();
        document.getElementById('orgModal').classList.remove('hidden');
    }

    function updateModalDisplay() {
        const profile = currentProfiles[currentIndex];
        
        // Set Teks
        document.getElementById('modal-nama').innerText = profile.nama || 'Nama Tidak Tersedia';
        document.getElementById('modal-jabatan').innerText = profile.jabatan || '-';
        
        // Set Gambar (FIX FOTO TIDAK MUNCUL)
        const imgElement = document.getElementById('modal-img');
        
        if (profile.gambar && profile.gambar !== 'null') {
            // PERBAIKAN: Hapus '/struktur' dari path asset.
            // Gunakan path 'storage/' langsung digabung dengan path dari database (struktur_organisasi/file.jpg)
            imgElement.src = "<?php echo e(asset('storage')); ?>/" + profile.gambar;
        } else {
            // Placeholder jika tidak ada foto
            imgElement.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(profile.nama) + "&background=random&size=200";
        }

        // Handle Navigasi (Next/Prev Buttons)
        const prevBtn = document.getElementById('prevPersonBtn');
        const nextBtn = document.getElementById('nextPersonBtn');
        const indicators = document.getElementById('slide-indicators');

        // Reset Indikator
        indicators.innerHTML = '';

        if (currentProfiles.length > 1) {
            // Munculkan tombol navigasi
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
            indicators.classList.remove('hidden');
            
            // Logic Tombol Disabled/Enabled
            if (currentIndex === 0) {
                prevBtn.classList.add('opacity-30', 'cursor-not-allowed');
                nextBtn.classList.remove('opacity-30', 'cursor-not-allowed');
            } else if (currentIndex === currentProfiles.length - 1) {
                prevBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                nextBtn.classList.add('opacity-30', 'cursor-not-allowed');
            } else {
                prevBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                nextBtn.classList.remove('opacity-30', 'cursor-not-allowed');
            }

            // Generate Dots Indikator
            currentProfiles.forEach((_, idx) => {
                let dot = document.createElement('span');
                dot.className = `w-2.5 h-2.5 rounded-full transition-all ${idx === currentIndex ? 'bg-white scale-125' : 'bg-white/40'}`;
                indicators.appendChild(dot);
            });

        } else {
            // Sembunyikan tombol jika cuma 1 orang
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            indicators.classList.add('hidden');
        }
    }

    function nextPerson() {
        if (currentIndex < currentProfiles.length - 1) {
            currentIndex++;
            updateModalDisplay();
        }
    }

    function prevPerson() {
        if (currentIndex > 0) {
            currentIndex--;
            updateModalDisplay();
        }
    }

    function closeOrgModal() {
        document.getElementById('orgModal').classList.add('hidden');
    }

    // Keyboard Shortcuts
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") { closeOrgModal(); }
        if (!document.getElementById('orgModal').classList.contains('hidden')) {
            if (event.key === "ArrowRight") nextPerson();
            if (event.key === "ArrowLeft") prevPerson();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-profil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\PBL_LSP\resources\views/landing_page/page_profil/struktur.blade.php ENDPATH**/ ?>