@use('App\Models\DataSertifikasiAsesi')

@php
    $pekerjaan = $asesi->dataPekerjaan->first();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    {{-- TAMBAHAN: SweetAlert2 untuk Pop Up Cantik --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        canvas { touch-action: none; }
    </style>
</head>

{{-- Body Background Original: bg-gray-100 --}}
<body class="bg-gray-100">

    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- 1. SIDEBAR (Desktop Only) --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>
        </div>

        {{-- 2. HEADER MOBILE (Component Baru) --}}
        @php
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                 $gambarSkema = asset('storage/' . $sertifikasi->jadwal->skema->gambar);
            }
        @endphp

        <x-mobile_header
            :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
            backUrl="{{ route('asesi.bukti.pemohon', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
        />

        {{-- 3. MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0" 
              data-sertifikasi-id="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            
            {{-- CONTAINER RESPONSIF --}}
            <div class="max-w-3xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all bg-white rounded-t-[40px] md:rounded-none md:bg-transparent min-h-screen md:min-h-0 shadow-2xl md:shadow-none">

                {{-- A. STEPPER --}}
                {{-- 1. Desktop --}}
                <div class="hidden md:flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div></div>
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">2</div></div>
                    <div class="w-24 h-0.5 bg-yellow-400 mx-4"></div>
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">3</div></div>
                </div>

                {{-- 2. Mobile (Kuning) --}}
                <div class="flex items-center justify-center mb-8 md:hidden pt-8 md:pt-0">
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">1</div></div>
                    <div class="w-16 h-1.5 bg-[#FFD700] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">2</div></div>
                    <div class="w-16 h-1.5 bg-[#FFD700] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">3</div></div>
                </div>

                {{-- JUDUL --}}
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center md:text-left">Tanda Tangan Pemohon</h1>

                {{-- INFO PEMOHON --}}
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
                            <p class="text-gray-900 font-medium text-lg">{{ $pekerjaan?->nama_institusi_pekerjaan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 block">Alamat Perusahaan</label>
                            <p class="text-gray-900 font-medium text-lg">{{ $pekerjaan?->alamat_institusi ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 text-gray-600 text-xs leading-relaxed">
                        Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan saya setuju untuk mengikuti proses sertifikasi sesuai dengan prosedur yang berlaku.
                    </div>
                </div>

                {{-- AREA TANDA TANGAN --}}
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital</label>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg bg-white relative overflow-hidden h-64">
                        {{-- Preview Gambar --}}
                        <div id="signature-image-container" class="w-full h-full flex items-center justify-center {{ $asesi->tanda_tangan ? '' : 'hidden' }}">
                            <img id="signature-image-prev" 
                                 src="{{ $asesi->tanda_tangan ? route('secure.file', ['path' => $asesi->tanda_tangan]) : '' }}" 
                                 class="max-h-full max-w-full object-contain" />
                        </div>

                        {{-- Canvas --}}
                        <div id="signature-canvas-container" class="w-full h-full {{ $asesi->tanda_tangan ? 'hidden' : '' }}">
                            <canvas id="signature-pad" class="w-full h-full cursor-crosshair block touch-none"></canvas>
                            <div id="signature-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="text-gray-400 text-sm">Tanda tangan di sini</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi TTD --}}
                    <div class="flex justify-between items-center mt-2">
                        <button type="button" id="clear-signature" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus & Ulangi
                        </button>
                        <p class="text-xs text-gray-500">Gunakan mouse atau jari (touchscreen)</p>
                    </div>
                </div>

                {{-- TOMBOL NAVIGASI --}}
                <div class="flex justify-between items-center mt-12 pt-6 border-t border-gray-100 pb-10 md:pb-0">
                    <a href="{{ route('asesi.bukti.pemohon', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition-all shadow-sm text-sm md:text-base">
                        Kembali
                    </a>

                    <button type="button" id="save-submit-btn"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all text-sm md:text-base shadow-blue-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Simpan
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- Script JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');
            const placeholder = document.getElementById('signature-placeholder');
            const imgContainer = document.getElementById('signature-image-container');
            const canvasContainer = document.getElementById('signature-canvas-container');
            const imgDisplay = document.getElementById('signature-image-display');
            const btnHapus = document.getElementById('clear-signature');
            const btnSimpan = document.getElementById('save-submit-btn');

            const idAsesi = "{{ $asesi->id_asesi ?? '' }}";
            const idSertifikasi = "{{ $sertifikasi->id_data_sertifikasi_asesi ?? '' }}";
            let hasSignature = {{ isset($asesi) && $asesi->tanda_tangan ? 'true' : 'false' }};

            if (!idAsesi || !idSertifikasi) {
                console.error("ID Asesi/Sertifikasi tidak ditemukan.");
                return;
            }

            let signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgba(255, 255, 255, 0)' });

            function resizeCanvas() {
                if (canvasContainer.classList.contains('hidden')) return;
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.addEventListener("resize", resizeCanvas);
            if (!hasSignature) setTimeout(resizeCanvas, 100);

            signaturePad.addEventListener("beginStroke", () => {
                placeholder.classList.add('hidden');
            });

            btnHapus.addEventListener('click', () => {
                imgContainer.classList.add('hidden');
                canvasContainer.classList.remove('hidden');
                placeholder.classList.remove('hidden');
                hasSignature = false;
                resizeCanvas();
                signaturePad.clear();
            });

            // --- LOGIC TOMBOL SIMPAN (MODIFIKASI KAK GEM) ---
            btnSimpan.addEventListener('click', async () => {
                let dataUrl = null;

                // 1. Cek validasi tanda tangan
                if (hasSignature && !imgContainer.classList.contains('hidden')) {
                    // Pakai gambar lama, dataUrl biarkan null
                    dataUrl = null; 
                } else {
                    if (signaturePad.isEmpty()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Harap tanda tangan terlebih dahulu!',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }
                    dataUrl = signaturePad.toDataURL('image/png');
                }

                // 2. TAMPILKAN POP UP KONFIRMASI SESUAI REQUEST
                const result = await Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Jawaban anda hanya dapat di kirim sekali dan tidak dapat di ganti!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563EB', // Blue-600
                    cancelButtonColor: '#d33',     // Red
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal'
                });

                // Jika user klik Batal, berhenti di sini (return)
                if (!result.isConfirmed) return;

                // 3. Kalau klik "Ya", lanjut proses simpan
                const originalText = btnSimpan.innerText;
                btnSimpan.innerText = 'Menyimpan...';
                btnSimpan.disabled = true;

                try {
                    const response = await fetch(`/api/v1/ajax-simpan-tandatangan/${idAsesi}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            data_tanda_tangan: dataUrl,
                            id_data_sertifikasi_asesi: idSertifikasi
                        })
                    });
                    const resultData = await response.json();
                    
                    if (response.ok && resultData.success) {
                        // Optional: Kasih notif sukses bentar sebelum pindah
                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data tersimpan.',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        window.location.href = "{{ route('asesi.payment.create', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";
                    } else {
                        throw new Error(resultData.message || 'Gagal menyimpan');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan: ' + error.message,
                    });
                    btnSimpan.innerText = originalText;
                    btnSimpan.disabled = false;
                }
            });
        });
    </script>

</body>
</html>