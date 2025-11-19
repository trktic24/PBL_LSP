@php
    // Kita ambil data pekerjaan pertama biar kodenya pendek
    // Tanda '?' (optional chaining) biar gak error kalau datanya kosong
    $pekerjaan = $asesi->dataPekerjaan->first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Library Signature Pad --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        canvas {
            touch-action: none;
            /* Biar gak scroll pas tanda tangan di HP */
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-sidebar :idAsesi="$asesi->id_asesi"></x-sidebar>

        {{-- Main Content --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">

                {{-- Stepper Progress --}}
                <div class="flex items-center justify-center mb-12">
                    {{-- Step 1 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1
                        </div>
                    </div>
                    
                    {{-- Garis 1-2 --}}
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    
                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            2
                        </div>
                    </div>
                    
                    {{-- Garis 2-3 --}}
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    
                    {{-- Step 3 (Aktif) --}}
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            3
                        </div>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-8">Tanda Tangan Pemohon</h1>

                {{-- Info Pemohon --}}
                <div class="space-y-4 text-sm mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <p class="text-base text-gray-800 font-semibold mb-4">Saya yang bertanda tangan di bawah ini:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 block">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium text-lg">{{ $asesi->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 block">Jabatan</label>
                            <p class="text-gray-900 font-medium text-lg">{{ $pekerjaan?->jabatan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 block">Perusahaan</label>
                            <p class="text-gray-900 font-medium text-lg">
                                {{ $pekerjaan?->nama_institusi_pekerjaan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 block">Alamat Perusahaan</label>
                            <p class="text-gray-900 font-medium text-lg">{{ $pekerjaan?->alamat_institusi ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 text-gray-600 text-xs leading-relaxed">
                        Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan saya setuju untuk
                        mengikuti proses sertifikasi sesuai dengan prosedur yang berlaku.
                    </div>
                </div>

                {{-- Area Tanda Tangan --}}
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital</label>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg bg-white relative overflow-hidden">

                        {{-- 1. Wadah Gambar Jadi (Preview) --}}
                        {{-- Kita kasih ID biar bisa diatur JS --}}
                        <div id="signature-image-container"
                            class="w-full h-64 flex items-center justify-center {{ $asesi->tanda_tangan ? '' : 'hidden' }}">
                            <img id="signature-image-display" {{-- Perhatikan fungsi asset() ini --}}
                                src="{{ $asesi->tanda_tangan ? asset($asesi->tanda_tangan) : '' }}"
                                class="max-h-full max-w-full object-contain" alt="Tanda Tangan">
                        </div>

                        {{-- 2. Wadah Canvas (Untuk Nulis) --}}
                        {{-- Kalau udah ada tanda tangan, canvas kita sembunyiin dulu --}}
                        <div id="signature-canvas-container"
                            class="w-full h-full {{ $asesi->tanda_tangan ? 'hidden' : '' }}">
                            <canvas id="signature-pad" class="w-full h-64 rounded-lg cursor-crosshair block"></canvas>

                            {{-- Placeholder text --}}
                            <div id="signature-placeholder"
                                class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="text-gray-400 text-sm">Tanda tangan di sini</span>
                            </div>
                        </div>

                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-between items-center mt-2">
                        <button type="button" id="clear-signature"
                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Hapus & Ulangi
                        </button>
                        <p class="text-xs text-gray-500">Gunakan mouse atau jari (touchscreen)</p>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                <div class="flex justify-between items-center mt-12 pt-6 border-t border-gray-100">
                    <a href="{{ route('bukti.pemohon', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="w-48 text-center px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-all shadow-sm">
                        Kembali
                    </a>

                    <button type="button" id="save-submit-btn"
                        class="w-48 text-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Selanjutnya 
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- ELEMEN DOM ---
            const canvas = document.getElementById('signature-pad');
            const placeholder = document.getElementById('signature-placeholder');

            // Container
            const imgContainer = document.getElementById('signature-image-container');
            const canvasContainer = document.getElementById('signature-canvas-container');

            // Elemen Gambar & Tombol
            const imgDisplay = document.getElementById('signature-image-display');
            const btnHapus = document.getElementById('clear-signature');
            const btnSimpan = document.getElementById('save-submit-btn');

            // Data dari Blade
            const idAsesi = "{{ $asesi->id_asesi }}";
            // Cek apakah user punya ttd saat halaman dimuat
            let hasSignature = {{ $asesi->tanda_tangan ? 'true' : 'false' }};

            // --- 1. SETUP SIGNATURE PAD ---
            let signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)'
            });

            function resizeCanvas() {
                // Hanya resize jika canvas sedang terlihat
                if (canvasContainer.classList.contains('hidden')) return;

                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);

                // Note: Resize akan menghapus coretan yang belum disave
                // signaturePad.clear(); 
            }
            window.addEventListener("resize", resizeCanvas);

            // Inisialisasi awal canvas (jika belum ada ttd)
            if (!hasSignature) {
                resizeCanvas();
            }

            // Hilangkan tulisan "Tanda tangan di sini" saat mulai nulis
            signaturePad.addEventListener("beginStroke", () => {
                placeholder.classList.add('hidden');
            });

            // --- 2. TOMBOL HAPUS / ULANGI ---
            btnHapus.addEventListener('click', () => {
                // Sembunyikan Gambar Preview
                imgContainer.classList.add('hidden');

                // Tampilkan Canvas
                canvasContainer.classList.remove('hidden');

                // Reset Canvas
                resizeCanvas();
                signaturePad.clear();
                placeholder.classList.remove('hidden');

                // Set status jadi 'sedang mengedit'
                hasSignature = false;
            });

            // --- 3. TOMBOL SIMPAN ---
            btnSimpan.addEventListener('click', async () => {

                // Skenario 1: User tidak melakukan perubahan (Gambar lama masih tampil)
                // Langsung lanjut aja ke halaman selesai
                if (hasSignature && !imgContainer.classList.contains('hidden')) {
                    window.location.href = '/form-selesai';
                    return;
                }

                // Skenario 2: User mau simpan tanda tangan baru, tapi kanvas kosong
                if (signaturePad.isEmpty()) {
                    alert("Harap tanda tangan terlebih dahulu!");
                    return;
                }

                // Skenario 3: Proses Simpan Baru / Update
                const originalText = btnSimpan.innerText;
                btnSimpan.innerText = 'Menyimpan...';
                btnSimpan.disabled = true;

                const dataUrl = signaturePad.toDataURL('image/png');

                try {
                    const response = await fetch(`/api/ajax-simpan-tandatangan/${idAsesi}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            data_tanda_tangan: dataUrl
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        alert('Tanda tangan berhasil diperbarui!');

                        // --- LOGIKA AUTO-UPDATE TAMPILAN ---
                        // 1. Update src gambar dengan path baru dari API
                        // 2. Tambahkan timestamp (?t=...) biar browser download gambar baru (bukan cache lama)
                        imgDisplay.src = `/${result.path}?t=${new Date().getTime()}`;

                        // 3. Tampilkan container gambar, sembunyikan canvas
                        imgContainer.classList.remove('hidden');
                        canvasContainer.classList.add('hidden');

                        // 4. Set status
                        hasSignature = true;

                        // 5. Reset tombol
                        btnSimpan.innerText = 'Simpan & Selesai';
                        btnSimpan.disabled = false;

                        // Opsional: Langsung pindah halaman kalau mau
                        window.location.href = '/form-selesai';

                    } else {
                        throw new Error(result.message || 'Gagal menyimpan');
                    }

                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                    btnSimpan.innerText = originalText;
                    btnSimpan.disabled = false;
                }
            });

        });
    </script>

</body>

</html>
