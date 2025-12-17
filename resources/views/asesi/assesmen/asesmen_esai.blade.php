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

        /* Timer Warning Animation */
        @keyframes pulse-red {
            0%, 100% { background-color: white; color: #DC2626; }
            50% { background-color: #FEE2E2; color: #991B1B; }
        }
        .timer-warning { animation: pulse-red 1s infinite; border-color: #EF4444 !important; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen font-sans antialiased">

    {{-- MODAL POPUP WAKTU HABIS --}}
    <div id="timeout-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border-t-8 border-red-500">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Waktu Asesmen Habis!</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Maaf, waktu pengerjaan asesmen Anda telah berakhir. Sistem sedang <strong>mengirimkan jawaban Anda secara otomatis</strong>.
                                </p>
                            </div>
                            <div class="mt-4 flex justify-center sm:justify-start items-center space-x-2 text-blue-600 font-semibold animate-pulse">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Mengumpulkan Jawaban...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 focus:outline-none relative">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">

                    {{-- HEADER --}}
                    <div class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 text-center mb-5 tracking-wide">
                            Pertanyaan Esai
                        </h1>
                        <div class="w-full border-b-2 border-gray-300 mb-6"></div>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                <span class="font-bold text-gray-800 text-lg">Skema :</span>
                                <div class="px-0 py-1.5 text-sm font-medium text-gray-700 flex-1 md:flex-none">
                                    {{ $sertifikasi->jadwal->skema->nama_skema ?? 'Skema Tidak Tersedia' }}
                                </div>
                            </div>

                            {{-- TIMER --}}
                            <div id="timer-box" class="bg-white border-2 border-gray-200 text-gray-800 px-5 py-2 rounded-lg font-mono font-bold flex items-center shadow-sm transition-colors duration-300">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="timer">Memuat...</span>
                            </div>
                        </div>
                    </div>

                    {{-- AREA SOAL --}}
                    <div class="flex flex-col lg:flex-row gap-6">
                        
                        {{-- KOLOM KIRI: SOAL --}}
                        <div class="flex-1 w-full lg:w-3/4">
                            
                            {{-- Skeleton Loading --}}
                            <div id="loading-skeleton" class="bg-white rounded-xl shadow-sm p-8 animate-pulse space-y-4 border border-gray-200">
                                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                <div class="h-6 bg-gray-300 rounded w-full"></div>
                                <div class="h-32 bg-gray-100 rounded w-full mt-6"></div>
                            </div>

                            {{-- Error Message --}}
                            <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-red-700" id="error-text"></p>
                            </div>

                            {{-- Container Soal --}}
                            <div id="question-container" class="hidden bg-white rounded-xl shadow-sm p-8 border border-gray-200 relative overflow-hidden">
                                {{-- Hiasan --}}
                                <div class="absolute top-0 right-0 -mt-6 -mr-6 text-blue-50 opacity-40 pointer-events-none">
                                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0 1 1 0 002 0zm-1 4a1 1 0 00-1 1v3a1 1 0 002 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                                <div class="relative z-10">
                                    {{-- Nomor Soal --}}
                                    <div class="mb-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            Soal No. <span id="current-question-num" class="ml-1">1</span>
                                        </span>
                                    </div>

                                    {{-- Teks Pertanyaan --}}
                                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-6 leading-relaxed" id="question-text"></h2>

                                    {{-- Input Jawaban (Textarea) --}}
                                    <div class="mb-2">
                                        <label for="essay-answer" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Jawaban Anda:</label>
                                        <textarea 
                                            id="essay-answer" 
                                            rows="10" 
                                            class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y bg-white text-gray-800 shadow-inner leading-relaxed"
                                            placeholder="Ketik jawaban Anda di sini secara detail dan jelas..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Navigasi --}}
                            <div class="flex justify-between items-center mt-6 font-medium">
                                <button id="btn-prev" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 flex items-center shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> 
                                    Sebelumnya
                                </button>
                                
                                <button id="btn-next" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center shadow-sm">
                                    Selanjutnya 
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                
                                <button id="btn-finish" class="hidden px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center shadow-sm">
                                    <span id="btn-finish-text">Kumpulkan Jawaban</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: NAVIGASI --}}
                        <aside class="lg:w-1/4">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 sticky top-6">
                                <h3 class="text-base font-bold mb-4 text-gray-900 border-b border-gray-100 pb-2">Navigasi Soal</h3>
                                <div class="grid grid-cols-5 gap-2" id="question-nav-grid"></div>
                                
                                <div class="mt-6 text-xs font-medium text-gray-500 space-y-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="flex items-center"><div class="w-3 h-3 bg-blue-600 rounded-sm mr-2"></div> Sedang dibuka</div>
                                    <div class="flex items-center"><div class="w-3 h-3 bg-green-500 rounded-sm mr-2"></div> Sudah diisi</div>
                                    <div class="flex items-center"><div class="w-3 h-3 border border-gray-300 bg-white rounded-sm mr-2"></div> Belum diisi</div>
                                </div>
                            </div>
                        </aside>

                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === VARIABEL TIMER DARI CONTROLLER ===
            // Pastikan Controller mengirim variabel $sisa_waktu
            let remainingSeconds = {{ $sisa_waktu ?? 0 }};

            // State
            let questionsData = [];
            let userAnswers = {}; // { "id_lembar_jawab": "text..." }
            let currentQuestionIndex = 0;
            let currentIdLembarJawab = null;
            let timerInterval;

            // DOM Elements
            const dom = {
                loading: document.getElementById('loading-skeleton'),
                container: document.getElementById('question-container'),
                errorMsg: document.getElementById('error-message'),
                errorText: document.getElementById('error-text'),
                questionText: document.getElementById('question-text'),
                essayInput: document.getElementById('essay-answer'),
                numLabel: document.getElementById('current-question-num'),
                prev: document.getElementById('btn-prev'),
                next: document.getElementById('btn-next'),
                finish: document.getElementById('btn-finish'),
                finishText: document.getElementById('btn-finish-text'),
                navGrid: document.getElementById('question-nav-grid'),
                timerDisplay: document.getElementById('timer'),
                timerBox: document.getElementById('timer-box'),
                modalTimeout: document.getElementById('timeout-modal'),
                csrf: document.querySelector('meta[name="csrf-token"]').content
            };

            // --- 1. TIMER LOGIC ---
            function startTimer() {
                updateTimerDisplay();
                if (remainingSeconds <= 0) {
                    handleTimeUp();
                    return;
                }
                timerInterval = setInterval(() => {
                    remainingSeconds--;
                    updateTimerDisplay();
                    if (remainingSeconds <= 0) {
                        clearInterval(timerInterval);
                        handleTimeUp();
                    }
                }, 1000);
            }

            function updateTimerDisplay() {
                if (remainingSeconds < 0) remainingSeconds = 0;
                const h = Math.floor(remainingSeconds / 3600).toString().padStart(2, '0');
                const m = Math.floor((remainingSeconds % 3600) / 60).toString().padStart(2, '0');
                const s = (remainingSeconds % 60).toString().padStart(2, '0');
                dom.timerDisplay.innerText = `${h}:${m}:${s}`;

                if (remainingSeconds <= 300) dom.timerBox.classList.add('timer-warning');
            }

            function handleTimeUp() {
                dom.modalTimeout.classList.remove('hidden');
                document.body.style.pointerEvents = 'none'; // Freeze UI
                submitAssessment(true); // Auto Submit
            }

            // --- 2. FETCH DATA ---
            async function fetchQuestions() {
                const idSertifikasi = window.location.pathname.split('/').pop();
                try {
                    // Pastikan route API ini sesuai dengan api.php kamu (asesmen-teori atau asesmen-esai)
                    // Kalau di api.php kamu pakai 'asesmen-esai', ganti url di bawah ini
                    const response = await fetch(`/api/v1/asesmen-esai/${idSertifikasi}/soal`); 
                    const result = await response.json();

                    if (result.success) {
                        questionsData = result.data;
                        if (questionsData.length === 0) throw new Error("Soal belum tersedia.");

                        // Load jawaban tersimpan
                        questionsData.forEach(q => {
                            if(q.jawaban_tersimpan) userAnswers[q.id_lembar_jawab] = q.jawaban_tersimpan;
                        });

                        initApp();
                    } else { throw new Error(result.message); }
                } catch (error) {
                    showError(error.message);
                    dom.timerDisplay.innerText = "Error";
                }
            }

            function initApp() {
                dom.loading.classList.add('hidden');
                dom.container.classList.remove('hidden');
                renderNavigationGrid();
                loadQuestion(0);
            }

            // --- 3. RENDER SOAL ---
            function loadQuestion(index) {
                if (index < 0 || index >= questionsData.length) return;
                currentQuestionIndex = index;
                const data = questionsData[index];
                currentIdLembarJawab = data.id_lembar_jawab;

                dom.numLabel.innerText = index + 1;
                dom.questionText.innerHTML = data.pertanyaan;
                dom.essayInput.value = userAnswers[currentIdLembarJawab] || '';

                updateNavigationButtons();
                updateNavGridStatus();
                // Scroll ke atas container soal
                dom.container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Save Input on Change
            dom.essayInput.addEventListener('input', (e) => {
                const val = e.target.value.trim();
                if (val) userAnswers[currentIdLembarJawab] = val;
                else delete userAnswers[currentIdLembarJawab];
                updateNavGridStatus();
            });

            // --- 4. NAVIGATION UI ---
            function renderNavigationGrid() {
                dom.navGrid.innerHTML = questionsData.map((_, i) => `
                    <button class="nav-btn-item w-full aspect-square rounded border text-sm font-medium text-gray-600 hover:border-blue-500 transition flex items-center justify-center focus:outline-none" 
                        onclick="jumpTo(${i})" data-index="${i}">${i + 1}</button>
                `).join('');
            }
            window.jumpTo = (i) => loadQuestion(i);

            function updateNavGridStatus() {
                document.querySelectorAll('.nav-btn-item').forEach(btn => {
                    const idx = parseInt(btn.dataset.index);
                    const id = questionsData[idx].id_lembar_jawab;
                    btn.className = `nav-btn-item w-full aspect-square rounded border text-sm font-medium transition focus:outline-none `;
                    
                    if (idx === currentQuestionIndex) btn.classList.add('nav-btn-active');
                    else if (userAnswers[id] && userAnswers[id].length > 0) btn.classList.add('nav-btn-answered');
                    else btn.classList.add('border-gray-200', 'text-gray-600');
                });
            }

            function updateNavigationButtons() {
                dom.prev.disabled = currentQuestionIndex === 0;
                const isLast = currentQuestionIndex === questionsData.length - 1;
                dom.next.classList.toggle('hidden', isLast);
                dom.finish.classList.toggle('hidden', !isLast);
            }

            // --- 5. SUBMIT LOGIC ---
            async function submitAssessment(isAuto = false) {
                if (!isAuto) {
                    const filled = Object.keys(userAnswers).length;
                    const total = questionsData.length;
                    if (filled < total) {
                        if(!confirm(`Anda baru mengisi ${filled} dari ${total} soal. Yakin ingin mengumpulkan?`)) return;
                    } else {
                        if(!confirm("Yakin ingin mengumpulkan semua jawaban?")) return;
                    }
                }

                if(!isAuto) {
                    dom.finish.disabled = true;
                    dom.finishText.innerText = 'Mengirim...';
                }

                const idSertifikasi = window.location.pathname.split('/').pop();
                const payload = Object.entries(userAnswers).map(([id, val]) => ({ id_lembar_jawab: id, jawaban: val }));

                try {
                    // Pastikan route API ini sesuai dengan api.php kamu
                    const response = await fetch(`/api/v1/asesmen-esai/${idSertifikasi}/submit`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': dom.csrf },
                        body: JSON.stringify({ jawaban: payload })
                    });
                    const result = await response.json();

                    if (response.ok && result.success) {
                        if(!isAuto) alert("Berhasil dikumpulkan!");
                        if (result.redirect_id_jadwal) window.location.href = '/asesi/tracker/' + result.redirect_id_jadwal;
                        else window.location.reload();
                    } else { throw new Error(result.message); }
                } catch (error) {
                    console.error(error);
                    if(isAuto) {
                        alert("Waktu habis, namun gagal mengirim otomatis. Silakan submit manual.");
                        document.body.style.pointerEvents = 'auto';
                        dom.modalTimeout.classList.add('hidden');
                    } else {
                        alert("Gagal mengirim: " + error.message);
                        dom.finish.disabled = false;
                        dom.finishText.innerText = 'Kumpulkan Jawaban';
                    }
                }
            }

            // Listeners
            dom.prev.onclick = () => loadQuestion(currentQuestionIndex - 1);
            dom.next.onclick = () => loadQuestion(currentQuestionIndex + 1);
            dom.finish.onclick = () => submitAssessment(false);

            function showError(msg) {
                dom.loading.classList.add('hidden');
                dom.errorMsg.classList.remove('hidden');
                dom.errorText.innerText = msg;
            }

            // --- START APP ---
            startTimer(); // Timer jalan duluan
            fetchQuestions(); // Baru fetch soal
        });
    </script>
</body>
</html>