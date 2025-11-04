<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banding Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya untuk area canvas tanda tangan */
        #signatureCanvas {
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 0.5rem; /* rounded-lg */
            cursor: crosshair;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <!-- Catatan: x-sidebar adalah custom component, diasumsikan berfungsi -->
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <!-- START: FORM WRAPPER -->
                <form id="bandingForm" method="POST" action="{{ route('banding.store') }}">
                    @csrf <!-- WAJIB untuk form POST di Laravel -->

                    <!-- Input Hidden Wajib untuk Controller Store -->
                    <input type="hidden" name="id_asesmen" value="{{ $dataAsesmen->id_asesmen ?? '' }}">
                    <input type="hidden" name="id_asesi" value="{{ $id_asesi ?? '' }}"> <!-- Diambil dari Controller -->
                    <input type="hidden" name="tanda_tangan_asesi" id="tanda_tangan_asesi" required>


                    <h1 class="text-4xl font-bold text-gray-900 mb-10">Banding Asesmen</h1>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">
                        <div class="col-span-1 font-medium text-gray-800">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                            <!-- Field TUK: Menggunakan value="1" agar request()->has('tuk_...') di Controller berhasil -->
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_sewaktu" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Sewaktu
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_tempatkerja" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Tempat Kerja
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_mandiri" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Mandiri
                            </label>
                        </div>
                        
                        <!-- Menampilkan data dari Controller (dataAsesmen) -->
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800">: {{ $dataAsesmen->nama_asesor ?? 'Data Kosong' }}</div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800">: {{ $dataAsesmen->nama_asesi ?? 'Data Kosong' }}</div>
                    </div>

                    <div class="text-sm text-gray-700 mb-6">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :</div>
                    
                    <div class="shadow border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Komponen</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Ya</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Tidak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Pertanyaan 1 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_1" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_1" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Pertanyaan 2 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_2" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_2" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Pertanyaan 3 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain' membantu Anda dalam Proses Banding?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_3" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_3" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-sm text-gray-700 space-y-2">
                        <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut:</p>
                        <!-- Menampilkan data dari Controller (dataAsesmen) -->
                        <p>Skema Sertifikasi: <span class="font-medium text-gray-900">{{ $dataAsesmen->skema_sertifikasi ?? 'Data Kosong' }}</span></p>
                        <p>No. Skema Sertifikasi: <span class="font-medium text-gray-900">{{ $dataAsesmen->no_skema_sertifikasi ?? 'Data Kosong' }}</span></p>
                    </div>

                    <div class="mt-6">
                        <label for="alasan" class="text-sm font-medium text-gray-700">Banding ini diajukan atas alasan sebagai berikut:</label>
                        <textarea id="alasan" name="alasan_banding" rows="4" placeholder="Berikan Keterangan Disini" required class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                    </div>

                    <div class="mt-6">
                        <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                            Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                        </p>
                        <div class="w-full h-32 bg-gray-50 border border-gray-300 rounded-lg shadow-inner mt-2">
                            <!-- Area Canvas untuk Tanda Tangan -->
                            <canvas id="signatureCanvas" class="w-full h-full"></canvas>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                            <button type="button" id="clearSignature" class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-12">
                        <button type="button" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                            Sebelumnya
                        </button>
                        <!-- Tombol Kirim bertipe submit untuk mengirim form -->
                        <button type="submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Kirim
                        </button>
                    </div>
                    
                </form>
                <!-- END: FORM WRAPPER -->

            </div>
        </main>

    </div>

    <!-- Script Tanda Tangan -->
    <script>
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        const clearButton = document.getElementById('clearSignature');
        const signatureInput = document.getElementById('tanda_tangan_asesi');
        const form = document.getElementById('bandingForm');

        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        // Fungsi untuk mengatur ulang ukuran Canvas agar responsif
        function resizeCanvas() {
            const rect = canvas.parentElement.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            // PENTING: Clear the canvas after resize
            ctx.clearRect(0, 0, canvas.width, canvas.height); 
            signatureInput.value = ''; // Reset input field on resize
        }

        // Inisialisasi dan resize awal
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function draw(e) {
            if (!isDrawing) return;
            
            e.preventDefault(); // Mencegah scrolling pada perangkat sentuh
            
            let clientX, clientY;

            // Mendeteksi apakah event berasal dari mouse atau touch
            if (e.touches) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }

            // Dapatkan posisi relatif terhadap canvas
            const rect = canvas.getBoundingClientRect();
            const currentX = clientX - rect.left;
            const currentY = clientY - rect.top;

            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();

            lastX = currentX;
            lastY = currentY;
        }
        
        function startDrawing(e) {
            isDrawing = true;
            let clientX, clientY;
            if (e.touches) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }
            const rect = canvas.getBoundingClientRect();
            lastX = clientX - rect.left;
            lastY = clientY - rect.top;
        }

        function stopDrawing() {
            isDrawing = false;
            // Jika tanda tangan sudah dibuat, simpan data ke hidden input
            if (canvas.toDataURL) {
                 signatureInput.value = canvas.toDataURL('image/png');
            }
        }

        // Listener untuk Mouse
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Listener untuk Touch/Sentuhan
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);
        canvas.addEventListener('touchcancel', stopDrawing);

        // Fungsi Hapus Tanda Tangan
        clearButton.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
        });

        // Verifikasi tanda tangan saat form dikirim (opsional tapi baik)
        form.addEventListener('submit', (e) => {
            if (!signatureInput.value) {
                // e.preventDefault();
                // alert('Anda harus menandatangani di area yang disediakan sebelum mengirim.');
            }
        });
    </script>
</body>
</html>