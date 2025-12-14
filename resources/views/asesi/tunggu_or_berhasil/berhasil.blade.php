@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahapan Selesai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Optional: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- ====================================================================== --}}
        {{-- 1. SIDEBAR (Desktop Only) --}}
        {{-- ====================================================================== --}}
        <div class="hidden md:block md:w-80 flex-shrink-0 bg-white border-r border-gray-200">
            {{-- Pastikan controller mengirim $asesi dan $sertifikasi --}}
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>
        </div>

        {{-- ====================================================================== --}}
        {{-- 2. HEADER MOBILE (Component Baru) --}}
        {{-- ====================================================================== --}}
        @php
            $gambarSkema = ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) 
                ? asset('images/skema/foto_skema/' . $sertifikasi->jadwal->skema->gambar) 
                : null;
        @endphp

        {{-- Note: backUrl di header mobile kita arahkan ke Tracker juga biar konsisten --}}
        <x-mobile_header
            :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
            backUrl="{{ route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]) }}"
        />

        {{-- ====================================================================== --}}
        {{-- 3. MAIN CONTENT (Universal Success Card) --}}
        {{-- ====================================================================== --}}
        <main class="flex-1 flex flex-col items-center justify-center p-6 md:p-12 relative overflow-hidden">
            
            {{-- Dekorasi Background (Opsional biar ga sepi) --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-10 right-10 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute bottom-10 left-10 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            </div>

            {{-- CARD UTAMA --}}
            <div class="w-full max-w-lg bg-white shadow-2xl rounded-3xl p-8 md:p-12 text-center border border-gray-100 transform transition-all hover:scale-[1.01] duration-300">
                
                {{-- Icon Checkmark Besar Animasi --}}
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-50 mb-8 ring-8 ring-green-50/50">
                    <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                {{-- Judul Universal --}}
                <h2 class="text-3xl font-bold text-gray-900 mb-4 tracking-tight">
                    Tahapan Selesai!
                </h2>

                {{-- Deskripsi Universal --}}
                <p class="text-gray-500 mb-10 text-lg leading-relaxed">
                    Terima kasih. Formulir ini telah Anda isi dan kirimkan sebelumnya. Data Anda sudah tersimpan dengan aman di sistem kami.
                </p>

                {{-- Tombol Kembali ke Tracker --}}
                <div class="flex justify-center">
                    <a href="{{ route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]) }}" 
                       class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white transition-all duration-200 bg-blue-600 rounded-full hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 w-full md:w-auto">
                        
                        {{-- Icon Panah Kiri --}}
                        <svg class="w-5 h-5 mr-2 -ml-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        
                        Kembali
                    </a>
                </div>
                
                {{-- Footer Kecil --}}
                <p class="mt-8 text-xs text-gray-400">
                    LSP POLINES
                </p>

            </div>
        </main>
    </div>

</body>
</html>