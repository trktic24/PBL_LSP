<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesmen Essai (IA-06)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        
        /* Style navigasi */
        .nav-btn-active { background-color: #3B82F6 !important; color: white !important; border-color: #3B82F6 !important; }
        .nav-btn-answered { background-color: #10B981; color: white; border-color: #10B981; }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased overflow-hidden">

    <div class="flex h-screen overflow-hidden bg-gray-100" x-data="{ sidebarOpen: false }">

        {{-- SIDEBAR (Asumsi komponen ini sudah benar) --}}
        {{-- Pastikan komponen x-sidebar2 ini punya lebar yang fix (misal w-64) --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 overflow-y-auto focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            
                    {{-- Header --}}
                    <div class="mb-8">
                        
                        {{-- 1. JUDUL UTAMA (Center, Tebal, Hitam - Persis Screenshot) --}}
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 text-center mb-5 tracking-wide">
                            Pertanyaan Esai
                        </h1>

                        {{-- 2. GARIS PEMBATAS (Tebal Abu-abu) --}}
                        <div class="w-full border-b-2 border-gray-300 mb-6"></div>

                        {{-- 3. INFO BAR (Di bawah garis: Kiri Info Skema, Kanan Timer) --}}
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            
                            {{-- Bagian Kiri: Info Skema (Mirip layout TUK di screenshot) --}}
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                <span class="font-bold text-gray-800 text-lg">Skema :</span>
                                {{-- Kotak tampilan skema --}}
                                <div class="px-0 py-1.5 text-sm font-medium text-gray-700 flex-1 md:flex-none">
                                    {{ $sertifikasi->jadwal->skema->nama_skema ?? 'Skema Tidak Tersedia' }}
                                </div>
                            </div>

                            {{-- Bagian Kanan: Timer (Tetap ada biar fungsional) --}}
                            <div class="bg-white border-2 border-gray-200 text-gray-800 px-5 py-2 rounded-lg font-mono font-bold flex items-center shadow-sm">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="timer">--:--</span>
                            </div>

                        </div>
                    </div>

                    {{-- Layout Kolom Dalam (Kiri Soal, Kanan Navigasi) --}}
                    <div class="flex flex-col lg:flex-row gap-6">

                        {{-- KOLOM KIRI: SOAL & INPUT ESSAI --}}
                        <div class="flex-1 w-full lg:w-3/4">
                            
                            {{-- Loading & Error State --}}
                            <div id="loading-skeleton" class="bg-white rounded-xl shadow-sm p-8 animate-pulse space-y-4 border border-gray-200">
                                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                <div class="h-6 bg-gray-300 rounded w-full"></div>
                                <div class="h-32 bg-gray-100 rounded w-full mt-6"></div>
                            </div>
                            <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded-md"></div>

                            {{-- WADAH UTAMA SOAL --}}
                            <div id="question-container" class="hidden bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                                
                                {{-- Nomor Soal --}}
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mb-4">
                                    Soal No. <span id="current-question-num">1</span>
                                </span>

                                {{-- Teks Pertanyaan --}}
                                <h2 class="text-lg font-semibold text-gray-900 mb-6 leading-relaxed" id="question-text"></h2>

                                {{-- INPUT JAWABAN ESSAI (TEXTAREA) --}}
                                <div class="mb-4">
                                    <label for="essay-answer" class="block text-sm font-medium text-gray-700 mb-2">Jawaban Anda:</label>
                                    <textarea 
                                        id="essay-answer" 
                                        rows="10" 
                                        class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y bg-white text-gray-800 shadow-sm"
                                        placeholder="Ketik jawaban Anda di sini secara detail dan jelas..."
                                    ></textarea>
                                </div>
                            </div>

                            {{-- Tombol Navigasi --}}
                            <div class="flex justify-between items-center mt-6">
                                <button id="btn-prev" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center disabled:opacity-50 shadow-sm">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Sebelumnya
                                </button>
                                
                                <button id="btn-next" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center shadow-sm">
                                    Selanjutnya <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                
                                <button id="btn-finish" class="hidden px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center shadow-sm">
                                    <span id="btn-finish-text">Kumpulkan Jawaban</span>
                                </button>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: NAVIGASI NOMOR --}}
                        <aside class="lg:w-1/4">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 sticky top-6">
                                <h3 class="text-base font-bold mb-4 text-gray-900">Navigasi Soal</h3>
                                <div class="grid grid-cols-5 gap-2" id="question-nav-grid"></div>
                                
                                <div class="mt-6 text-xs font-medium text-gray-500 space-y-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="flex items-center"><div class="w-3 h-3 bg-blue-600 rounded-sm mr-2"></div> Sedang dibuka (Aktif)</div>
                                    <div class="flex items-center"><div class="w-3 h-3 bg-green-500 rounded-sm mr-2"></div> Sudah ada isinya</div>
                                    <div class="flex items-center"><div class="w-3 h-3 border border-gray-300 bg-white rounded-sm mr-2"></div> Belum diisi</div>
                                </div>
                            </div>
                        </aside>

                    </div> {{-- End Flex Col/Row --}}
                </div>
            </div>
        </main>
    </div> {{-- End Wrapper Flex --}}


    {{-- JAVASCRIPT LOGIC (Disesuaikan untuk Textarea) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let questionsData = [];
            let userAnswers = {}; // Format: { "id_lembar_jawab": "Teks jawaban..." }
            let currentQuestionIndex = 0;
            let currentIdLembarJawab = null;

            // Elements
            const loadingSkeleton = document.getElementById('loading-skeleton');
            const questionContainer = document.getElementById('question-container');
            const errorMessageEl = document.getElementById('error-message');
            const questionTextEl = document.getElementById('question-text');
            const essayAnswerEl = document.getElementById('essay-answer'); // Textarea
            const currentNumEl = document.getElementById('current-question-num');
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnFinish = document.getElementById('btn-finish');
            const navGridEl = document.getElementById('question-nav-grid');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // 1. Fetch Data
            async function fetchQuestions() {
                const idSertifikasi = window.location.pathname.split('/').pop();
                if(isNaN(idSertifikasi)) return showError("ID Sertifikasi tidak valid.");

                try {
                    // Endpoint API Essai
                    const response = await fetch(`/api/v1/asesmen-esai/${idSertifikasi}/soal`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();

                    if (result.success) {
                        questionsData = result.data;
                        if (questionsData.length === 0) return showError(result.message);

                        // Load jawaban tersimpan
                        questionsData.forEach(q => {
                            if(q.jawaban_tersimpan) userAnswers[q.id_lembar_jawab] = q.jawaban_tersimpan;
                        });

                        initApp();
                    } else { throw new Error(result.message); }
                } catch (error) { showError(error.message); }
            }

            function showError(msg) {
                loadingSkeleton.add('hidden'); questionContainer.add('hidden');
                errorMessageEl.remove('hidden'); errorMessageEl.innerText = msg;
            }

            function initApp() {
                loadingSkeleton.classList.add('hidden');
                questionContainer.classList.remove('hidden');
                renderNavigationGrid();
                loadQuestion(0);
            }

            // 2. Render Navigation
            function renderNavigationGrid() {
                navGridEl.innerHTML = '';
                questionsData.forEach((_, index) => {
                    const btn = document.createElement('button');
                    btn.innerText = index + 1;
                    btn.className = `w-full aspect-square rounded border text-sm font-medium text-gray-600 hover:border-blue-500 transition flex items-center justify-center nav-btn-item`;
                    btn.dataset.index = index;
                    btn.addEventListener('click', () => loadQuestion(index));
                    navGridEl.appendChild(btn);
                });
                updateNavGridStatus();
            }

            // 3. Load Single Question
            function loadQuestion(index) {
                if (index < 0 || index >= questionsData.length) return;
                currentQuestionIndex = index;
                const data = questionsData[index];
                currentIdLembarJawab = data.id_lembar_jawab;

                currentNumEl.innerText = index + 1;
                questionTextEl.innerHTML = data.pertanyaan;
                
                // Isi Textarea dengan jawaban sementara (atau kosong)
                essayAnswerEl.value = userAnswers[currentIdLembarJawab] || '';
                
                updateNavigationButtons();
                updateNavGridStatus();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // 4. Save Answer (Triggered on textarea change/input)
            essayAnswerEl.addEventListener('input', (e) => {
                const answerText = e.target.value.trim();
                // Simpan teks, kalau kosong anggap null/dihapus
                if (answerText) {
                    userAnswers[currentIdLembarJawab] = answerText;
                } else {
                    delete userAnswers[currentIdLembarJawab];
                }
                updateNavGridStatus();
            });

            // 5. Navigation Logic
            function updateNavigationButtons() {
                btnPrev.disabled = currentQuestionIndex === 0;
                const isLast = currentQuestionIndex === questionsData.length - 1;
                btnNext.classList.toggle('hidden', isLast);
                btnFinish.classList.toggle('hidden', !isLast);
            }

            function updateNavGridStatus() {
                document.querySelectorAll('.nav-btn-item').forEach(btn => {
                    const idx = parseInt(btn.dataset.index);
                    const id = questionsData[idx].id_lembar_jawab;
                    btn.classList.remove('nav-btn-active', 'nav-btn-answered');
                    if (idx === currentQuestionIndex) btn.classList.add('nav-btn-active');
                    // Cek apakah ada teks jawaban untuk soal ini
                    else if (userAnswers[id] && userAnswers[id].length > 0) btn.classList.add('nav-btn-answered');
                });
            }

            btnPrev.onclick = () => loadQuestion(currentQuestionIndex - 1);
            btnNext.onclick = () => loadQuestion(currentQuestionIndex + 1);

            // 6. Submit Logic
            btnFinish.onclick = async () => {
                const answeredCount = Object.keys(userAnswers).length;
                const total = questionsData.length;

                if (answeredCount < total) {
                    if(!confirm(`Anda baru mengisi ${answeredCount} dari ${total} soal essai. Yakin ingin mengumpulkan?`)) return;
                } else {
                    if(!confirm("Kumpulkan semua jawaban essai Anda?")) return;
                }

                const payload = Object.entries(userAnswers).map(([id, val]) => ({ id_lembar_jawab: id, jawaban: val }));
                const idSertifikasi = window.location.pathname.split('/').pop();
                
                btnFinish.disabled = true; btnFinish.innerText = 'Mengirim...';

                try {
                    const response = await fetch(`/api/v1/asesmen-esai/${idSertifikasi}/submit`, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ jawaban: payload })
                    });
                    const result = await response.json();

                    if (response.ok && result.success) {
                        alert(result.message);
                        if (result.redirect_id_jadwal) window.location.href = '/tracker/' + result.redirect_id_jadwal;
                    } else { throw new Error(result.message); }
                } catch (error) {
                    alert(`Gagal: ${error.message}`);
                    btnFinish.disabled = false; btnFinish.innerText = 'Kumpulkan Jawaban';
                }
            };

            // Start App
            fetchQuestions();
        });
    </script>
</body>
</html>