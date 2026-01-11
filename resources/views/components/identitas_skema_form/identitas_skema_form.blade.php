@props([
    'sertifikasi' => null, // Terima objek sertifikasi
    'skema' => 'Data Skema Default',
    'nomorSkema' => 'Nomor Skema Default',
    'tuk' => 'Tempat Kerja', // Nilai default
    'namaAsesor' => 'Data Asesor Default',
    'namaAsesi' => 'Data Asesi Default',
    'tanggal' => 'Tanggal Default',
    'waktu' => '09.00 WIB',
    'showWaktu' => true
])

@php
    // Jika sertifikasi ada, override variabel default dengan data dari sertifikasi
    if ($sertifikasi) {
        // Skema - gunakan 'nama_skema' (bukan judul_skema)
        $skema = $sertifikasi->jadwal->skema->nama_skema ?? '-';
        $nomorSkema = $sertifikasi->jadwal->skema->nomor_skema ?? '-';
        
        // TUK - ambil dari jenisTuk (jenis_tuk) untuk radio button
        $tukJenis = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? null;
        $tuk = $tukJenis ?? ($sertifikasi->jadwal->tuk->nama_tuk ?? '-');
        
        // Asesor - ambil dari jadwal->asesor (bukan skema->asesor)
        $namaAsesor = $sertifikasi->jadwal->asesor->nama_lengkap ?? '-';
        
        // Asesi
        $namaAsesi = $sertifikasi->asesi->nama_lengkap ?? '-';
        
        // Tanggal - format dari Carbon ke string
        $tanggalRaw = $sertifikasi->jadwal->tanggal_pelaksanaan ?? null;
        $tanggal = $tanggalRaw ? $tanggalRaw->format('d-m-Y') : '-';
        
        // Waktu - format dari Carbon ke string
        $waktuRaw = $sertifikasi->jadwal->waktu_mulai ?? null;
        $waktu = $waktuRaw ? $waktuRaw->format('H:i') . ' WIB' : '-';
    }
@endphp

{{-- 
  HTML, isinya diganti dengan variabel props.
--}}

{{-- 
  UBAH DI SINI: 
  - Default (Mobile): grid-cols-1 (satu kolom)
  - Desktop (md): Terapkan grid 2 kolom-mu
  - items-start (mobile) agar label rapi, md:items-center (desktop)
--}}
<div {{ $attributes->merge(['class' => 'form-row grid grid-cols-1 md:grid-cols-[250px_1fr] md:gap-x-6 gap-y-1.5 items-start md:items-center']) }}>
    
    <label class="text-sm font-bold text-black">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
    <div class="flex items-center">
        <p class="ml-2 font-medium text-gray-600"></p>
    </div>

    <label class="text-sm font-bold text-black">Judul</label>
    <div class="flex items-center">
        <span>:</span>
        <p class="ml-2 font-medium text-gray-600">{{ $skema }}</p>
    </div>

    <label class="text-sm font-bold text-black">Nomor</label>
    <div class="flex items-center">
        <span>:</span>
        <p class="ml-2 font-medium text-gray-600">{{ $nomorSkema }}</p>
    </div>

    <label class="text-sm font-bold text-black">TUK</label>
    {{-- 
      UBAH DI SINI: 
      - Default (Mobile): flex-col (susun ke bawah) & items-start
      - Desktop (md): flex-row (susun ke samping) & items-center
    --}}
    <div class="radio-group flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-4">
        <span>:</span>
        {{-- 
          UBAH DI SINI: 
          - Default (Mobile): ml-0 (tidak ada margin)
          - Desktop (md): ml-2 (tambahkan margin)
        --}}
        <div class="flex items-center space-x-2 ml-0 md:ml-2">
            {{-- Gunakan '@checked' untuk menentukan pilihan default --}}
            <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" 
                   @checked($tuk == 'Sewaktu') disabled>
            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
        </div>
        <div class="flex items-center space-x-2">
            <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-gray-400"
                   @checked($tuk == 'Tempat Kerja') disabled>
            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
        </div>
    </div>

    <label class="text-sm font-bold text-black">Nama Asesor</label>
    <div class="flex items-center">
        <span>:</span>
        <p class="ml-2 font-medium text-gray-600">{{ $namaAsesor }}</p>
    </div>
    
    <label class="text-sm font-bold text-black">Nama Asesi</label>
    <div class="flex items-center">
        <span>:</span>
        <p class="ml-2 font-medium text-gray-600">{{ $namaAsesi }}</p>
    </div>

    <label class="text-sm font-bold text-black">Tanggal</label>
    <div class="flex items-center">
        <span>:</span>
        <p class="ml-2 font-medium text-gray-600">{{ $tanggal }}</p>
    </div>

    @if($showWaktu)
        <label class="text-sm font-bold text-black">Waktu</label>
        <div class="flex items-center">
            <span>:</span>
            <p class="ml-2 font-medium text-gray-600">{{ $waktu }}</p>
        </div>
    @endif    
</div>