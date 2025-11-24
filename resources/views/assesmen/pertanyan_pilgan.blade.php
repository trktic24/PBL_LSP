<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesmen Pilihan Ganda (IA-05)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Meta CSRF Token untuk request AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Style untuk opsi pilihan ganda saat dipilih */
        .option-label:has(input:checked) {
            background-color: #EFF6FF;
            /* bg-blue-50 */
            border-color: #3B82F6;
            /* border-blue-500 */
        }

        .option-label:has(input:checked) .option-key {
            background-color: #3B82F6;
            /* bg-blue-500 */
            border-color: #3B82F6;
            color: white;
        }

        /* Style untuk navigasi nomor soal */
        .nav-btn-active {
            background-color: #3B82F6 !important;
            color: white !important;
            border-color: #3B82F6 !important;
        }

        .nav-btn-answered {
            background-color: #10B981;
            /* Green for answered */
            color: white;
            border-color: #10B981;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen font-sans antialiased">

    {{-- LAYOUT UTAMA DENGAN SIDEBAR --}}
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR (Menggunakan Komponen x-sidebar2) --}}
        {{-- Pastikan variabel $asesi dan $sertifikasi dikirim dari Controller --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">

                    {{-- Header Halaman --}}
                    <div class="md:flex md:items-center md:justify-between mb-6">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                Asesmen Teori (IA-05)
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Skema: {{ $sertifikasi->jadwal->skema->nama_skema ?? 'Nama Skema Tidak Tersedia' }}
                            </p>
                        </div>
                        {{-- Timer (Placeholder) --}}
                        <div class="mt-4 flex md:mt-0 md:ml-4">
                            <div
                                class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-mono font-bold flex items-center shadow-sm">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="timer">--:--</span>
                            </div>
                        </div>
                    </div>

                    {{-- Layout Kolom: Kiri (Soal) & Kanan (Navigasi) --}}
                    <div class="flex flex-col lg:flex-row gap-6">

                        {{-- KOLOM KIRI: AREA SOAL --}}
                        <div class="flex-1 lg:w-3/4">

                            {{-- Loading State --}}
                            <div id="loading-skeleton"
                                class="bg-white rounded-xl shadow-sm p-8 animate-pulse border border-gray-200">
                                <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                                <div class="h-6 bg-gray-300 rounded w-full mb-2"></div>
                                <div class="h-6 bg-gray-300 rounded w-3/4 mb-8"></div>
                                <div class="space-y-4">
                                    <div class="h-14 bg-gray-100 rounded-lg block border border-gray-200"></div>
                                    <div class="h-14 bg-gray-100 rounded-lg block border border-gray-200"></div>
                                    <div class="h-14 bg-gray-100 rounded-lg block border border-gray-200"></div>
                                    <div class="h-14 bg-gray-100 rounded-lg block border border-gray-200"></div>
                                </div>
                            </div>

                            {{-- Pesan Error / Kosong --}}
                            <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-red-700" id="error-text"></p>
                            </div>

                            {{-- Wadah Soal (Akan diisi oleh JS) --}}
                            <div id="question-container" class="hidden">
                                <div
                                    class="bg-white rounded-xl shadow-sm p-8 border border-gray-200 relative overflow-hidden">
                                    {{-- Hiasan Background --}}
                                    <div
                                        class="absolute top-0 right-0 -mt-6 -mr-6 text-blue-50 opacity-40 pointer-events-none">
                                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0 1 1 0 002 0zm-1 4a1 1 0 00-1 1v3a1 1 0 002 0v-3a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>

                                    <div class="relative z-10">
                                        {{-- Nomor Soal --}}
                                        <div class="mb-6">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                Soal No. <span id="current-question-num" class="ml-1">1</span>
                                            </span>
                                        </div>

                                        {{-- Teks Pertanyaan --}}
                                        <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-8 leading-relaxed"
                                            id="question-text">
                                        </h2>

                                        {{-- Opsi Jawaban --}}
                                        <div class="space-y-4" id="options-container">
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Navigasi Bawah (Prev/Next) --}}
                                <div class="flex justify-between items-center mt-6 font-medium">
                                    <button id="btn-prev"
                                        class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center shadow-sm">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Sebelumnya
                                    </button>

                                    <button id="btn-next"
                                        class="px-5 py-2.5 bg-blue-600 border border-transparent text-white rounded-lg hover:bg-blue-700 transition flex items-center shadow-sm">
                                        Selanjutnya
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    {{-- Tombol Selesai (Muncul di soal terakhir) --}}
                                    <button id="btn-finish"
                                        class="hidden px-5 py-2.5 bg-green-600 border border-transparent text-white rounded-lg hover:bg-green-700 transition flex items-center shadow-sm">
                                        <span id="btn-finish-text">Selesai & Kumpulkan</span>
                                        <svg id="btn-finish-icon" class="w-5 h-5 ml-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: NAVIGASI NOMOR --}}
                        <aside class="lg:w-1/4">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 sticky top-6">
                                <h3 class="text-base font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                                    Navigasi Soal</h3>

                                {{-- Grid Nomor Soal --}}
                                <div class="grid grid-cols-5 gap-2" id="question-nav-grid">
                                </div>

                                {{-- Legenda Keterangan --}}
                                <div class="mt-6 text-xs text-gray-500 space-y-2 bg-gray-50 p-3 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-600 rounded-sm mr-2"></div> Sedang dibuka
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-sm mr-2"></div> Sudah dijawab
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 border border-gray-300 bg-white rounded-sm mr-2"></div>
                                        Belum dijawab
                                    </div>
                                </div>
                            </div>
                        </aside>

                    </div> {{-- End Flex Row --}}
                </div>
            </div>
        </main>
    </div>


    {{-- JAVASCRIPT LOGIC (Disesuaikan dengan API baru) --}}
    {{-- JAVASCRIPT LOGIC (FULL VERSION WITH V1 API & REDIRECT FIX) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. VARIABEL STATE UTAMA ---
            let questionsData = []; // Menyimpan semua data soal dari API
            // userAnswers menyimpan jawaban user.
            // KUNCI-nya adalah ID LEMBAR JAWAB (string), NILAI-nya adalah jawaban (a,b,c,d)
            let userAnswers = {};
            let currentQuestionIndex = 0; // Indeks array soal yang sedang aktif (mulai dari 0)

            // Elemen DOM
            const loadingSkeleton = document.getElementById('loading-skeleton');
            const questionContainer = document.getElementById('question-container');
            const errorMessageEl = document.getElementById('error-message');
            const errorTextEl = document.getElementById('error-text');
            const questionTextEl = document.getElementById('question-text');
            const optionsContainerEl = document.getElementById('options-container');
            const currentNumEl = document.getElementById('current-question-num');
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnFinish = document.getElementById('btn-finish');
            const btnFinishText = document.getElementById('btn-finish-text');
            const btnFinishIcon = document.getElementById('btn-finish-icon');
            const navGridEl = document.getElementById('question-nav-grid');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;


            // --- 2. FUNGSI UNTUK MENGAMBIL DATA DARI API LARAVEL (GUNAKAN V1) ---
            async function fetchQuestions() {
                // Ambil ID Sertifikasi dari URL. Asumsi URL: /asesmen/ia05/{id_sertifikasi}
                const pathSegments = window.location.pathname.split('/');
                const idSertifikasi = pathSegments[pathSegments.length - 1];

                if (!idSertifikasi || isNaN(idSertifikasi)) {
                    showError("ID Sertifikasi tidak valid di URL.");
                    return;
                }

                try {
                    // Panggil endpoint GET dengan prefix /api/v1/
                    const response = await fetch(`/api/v1/asesmen-teori/${idSertifikasi}/soal`, {
                        headers: {
                            'Accept': 'application/json',
                            // Jika pakai token auth (Sanctum), tambahkan header Authorization di sini
                            // 'Authorization': 'Bearer ' + your_token
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    const result = await response.json();

                    if (result.success) {
                        questionsData = result.data;

                        // Jika data kosong (belum ada soal untuk skema ini)
                        if (questionsData.length === 0) {
                            showError(
                                "Belum ada soal yang tersedia untuk skema ini atau Anda belum dijadwalkan.");
                            return;
                        }

                        // Populate jawaban yang sudah tersimpan dari database (jika ada)
                        questionsData.forEach(q => {
                            if (q.jawaban_tersimpan) {
                                // KUNCI UTAMA: Gunakan id_lembar_jawab
                                userAnswers[q.id_lembar_jawab] = q.jawaban_tersimpan;
                            }
                        });

                        // Data siap, mulai inisialisasi tampilan
                        initApp();

                    } else {
                        throw new Error(result.message || 'Gagal mengambil data soal.');
                    }

                } catch (error) {
                    console.error("Error fetching questions:", error);
                    showError(`Terjadi kesalahan saat memuat soal: ${error.message}`);
                }
            }

            // Helper untuk menampilkan error
            function showError(message) {
                loadingSkeleton.classList.add('hidden');
                questionContainer.classList.add('hidden');
                errorMessageEl.classList.remove('hidden');
                errorTextEl.innerText = message;
            }


            // --- 3. FUNGSI INISIALISASI TAMPILAN ---
            function initApp() {
                loadingSkeleton.classList.add('hidden');
                errorMessageEl.classList.add('hidden');
                questionContainer.classList.remove('hidden');

                // Buat grid navigasi nomor di sidebar
                renderNavigationGrid();
                // Tampilkan soal pertama (index 0)
                loadQuestion(0);
            }


            // --- 4. FUNGSI RENDER NAVIGASI NOMOR (SIDEBAR) ---
            function renderNavigationGrid() {
                navGridEl.innerHTML = '';
                // Looping sebanyak data soal yang didapat
                questionsData.forEach((question, index) => {
                    const pageNumber = index + 1;
                    const navBtn = document.createElement('button');
                    navBtn.innerText = pageNumber;
                    navBtn.className =
                        `w-full aspect-square rounded-lg border border-gray-200 text-sm font-medium text-gray-600 hover:border-blue-500 hover:text-blue-600 transition flex items-center justify-center nav-btn-item focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1`;
                    navBtn.dataset.index = index; // Simpan index array sebagai data attribute

                    navBtn.addEventListener('click', () => {
                        loadQuestion(index);
                    });

                    navGridEl.appendChild(navBtn);
                });
                updateNavGridStatus();
            }


            // --- 5. FUNGSI UNTUK MEMUAT/MENAMPILKAN SATU SOAL ---
            function loadQuestion(index) {
                if (index < 0 || index >= questionsData.length) return;

                currentQuestionIndex = index;
                const currentQuestionData = questionsData[index];

                // Update UI Teks Soal & Nomor
                currentNumEl.innerText = index + 1;
                // Gunakan nama kolom yang sesuai dari API (pertanyaan)
                questionTextEl.innerHTML = currentQuestionData.pertanyaan;

                // Render Opsi Jawaban
                optionsContainerEl.innerHTML = '';
                currentQuestionData.opsi.forEach(opsi => {
                    // Cek apakah opsi ini sudah dipilih sebelumnya (gunakan id_lembar_jawab sebagai kunci)
                    const isChecked = userAnswers[currentQuestionData.id_lembar_jawab] === opsi.key;

                    const optionHtml = `
                    <label class="option-label flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition w-full bg-white shadow-sm relative overflow-hidden">
                        <input type="radio" 
                                name="question_${currentQuestionData.id_lembar_jawab}" 
                                value="${opsi.key}" 
                                class="hidden peer"
                                ${isChecked ? 'checked' : ''}
                                onchange="saveAnswer(${currentQuestionData.id_lembar_jawab}, '${opsi.key}')">
                        
                        <div class="option-key w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 font-bold mr-4 transition uppercase">
                            ${opsi.key}
                        </div>
                        <span class="text-gray-800 font-medium text-base">${opsi.text}</span>
                    </label>
                `;
                    optionsContainerEl.insertAdjacentHTML('beforeend', optionHtml);
                });

                // Update status tombol Navigasi Bawah & Sidebar
                updateNavigationButtons();
                updateNavGridStatus();
                // Scroll ke atas container soal agar nyaman dibaca
                questionContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            // --- 6. FUNGSI UPDATE TOMBOL NEXT/PREV/FINISH ---
            function updateNavigationButtons() {
                // Tombol Previous mati di soal pertama
                btnPrev.disabled = currentQuestionIndex === 0;

                // Cek apakah ini soal terakhir
                const isLastQuestion = currentQuestionIndex === questionsData.length - 1;

                if (isLastQuestion) {
                    btnNext.classList.add('hidden');
                    btnFinish.classList.remove('hidden');
                } else {
                    btnNext.classList.remove('hidden');
                    btnFinish.classList.add('hidden');
                }
            }

            // --- 7. FUNGSI UPDATE STATUS NAVIGASI GRID ---
            function updateNavGridStatus() {
                document.querySelectorAll('.nav-btn-item').forEach(btn => {
                    const index = parseInt(btn.dataset.index);
                    // Ambil ID lembar jawab dari data soal pada index tersebut
                    const idLembarJawab = questionsData[index].id_lembar_jawab;

                    // Reset class
                    btn.classList.remove('nav-btn-active', 'nav-btn-answered');

                    // Cek jika ini soal yang sedang dibuka
                    if (index === currentQuestionIndex) {
                        btn.classList.add('nav-btn-active');
                    }
                    // Cek jika soal ini sudah dijawab (cek di objek userAnswers pakai id_lembar_jawab)
                    else if (userAnswers[idLembarJawab]) {
                        btn.classList.add('nav-btn-answered');
                    }
                });
            }


            // --- 8. FUNGSI SIMPAN JAWABAN SEMENTARA DI JS ---
            // Dipanggil saat radio button diklik (onchange) di HTML
            window.saveAnswer = function(idLembarJawab, selectedOptionKey) {
                // KUNCI UTAMA: ID LEMBAR JAWAB
                userAnswers[idLembarJawab] = selectedOptionKey;
                updateNavGridStatus(); // Update warna grid jadi hijau
            };


            // --- 9. EVENT LISTENERS TOMBOL NAVIGASI ---
            btnPrev.addEventListener('click', () => {
                loadQuestion(currentQuestionIndex - 1);
            });

            btnNext.addEventListener('click', () => {
                loadQuestion(currentQuestionIndex + 1);
            });


            // --- 10. EVENT LISTENER TOMBOL FINISH (SUBMIT KE API V1 & REDIRECT FIX) ---
            btnFinish.addEventListener('click', async () => {
                // Hitung berapa soal yang sudah dijawab
                const totalAnswered = Object.keys(userAnswers).length;
                const totalQuestions = questionsData.length;

                // Validasi: Pastikan semua soal sudah dijawab
                if (totalAnswered < totalQuestions) {
                    alert(
                        `Anda baru menjawab ${totalAnswered} dari ${totalQuestions} soal.\n\nMohon lengkapi semua jawaban sebelum mengumpulkan.`);
                    return;
                }

                if (!confirm(
                        "Apakah Anda yakin ingin mengumpulkan jawaban? Pastikan semua jawaban sudah benar."
                        )) return;

                // Siapkan payload data untuk dikirim ke API
                const payload = Object.entries(userAnswers).map(([id, val]) => ({
                    id_lembar_jawab: id,
                    jawaban: val
                }));

                // Ambil ID Sertifikasi dari URL lagi
                const pathSegments = window.location.pathname.split('/');
                const idSertifikasi = pathSegments[pathSegments.length - 1];

                // Update UI tombol saat loading
                btnFinish.disabled = true;
                btnFinishText.innerText = 'Mengirim...';
                btnFinishIcon.classList.add('hidden');

                try {
                    // Panggil endpoint POST dengan prefix /api/v1/
                    const response = await fetch(`/api/v1/asesmen-teori/${idSertifikasi}/submit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                            // Tambahkan token auth jika perlu
                        },
                        body: JSON.stringify({
                            jawaban: payload
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        alert(result.message || "Jawaban berhasil dikumpulkan!");

                        // --- [PERBAIKAN REDIRECT DI SINI] ---
                        // Gunakan ID JADWAL yang dikirim balik oleh backend untuk redirect
                        if (result.redirect_id_jadwal) {
                            window.location.href = '/tracker/' + result.redirect_id_jadwal;
                        } else {
                            // Fallback jika backend lupa mengirim ID (seharusnya tidak terjadi)
                            console.warn("Backend tidak mengirim redirect_id_jadwal.");
                            // window.location.reload(); // Opsi lain: reload halaman
                        }

                    } else {
                        throw new Error(result.message || 'Gagal menyimpan jawaban.');
                    }

                } catch (error) {
                    console.error("Error submitting answers:", error);
                    alert(`Gagal mengumpulkan jawaban: ${error.message}. Silakan coba lagi.`);

                    // Kembalikan UI tombol jika gagal
                    btnFinish.disabled = false;
                    btnFinishText.innerText = 'Selesai & Kumpulkan';
                    btnFinishIcon.classList.remove('hidden');
                }
            });


            // --- MULAI APLIKASI ---
            fetchQuestions(); // Panggil fungsi pengambil data pertama kali saat halaman dimuat
        });
    </script>

</body>

</html>
