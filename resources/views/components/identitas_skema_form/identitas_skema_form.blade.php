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
        // Fix 1: Use 'nama_skema' instead of 'judul_skema' (based on Skema model)
        $skema = $sertifikasi->jadwal->skema->nama_skema ?? '-';
        $nomorSkema = $sertifikasi->jadwal->skema->nomor_skema ?? '-';
        
        // Fix TUK Display Logic
        $tukData = $sertifikasi->jadwal->masterTuk ?? $sertifikasi->jadwal->tuk;
        $tuk = $tukData->nama_lokasi ?? $tukData->nama_tuk ?? '-';
        
        // Fix 2: Get Asesor from Jadwal (not random form Skema) and use 'nama_lengkap'
        $namaAsesor = $sertifikasi->jadwal->asesor->nama_lengkap ?? '-';
        
        $namaAsesi = $sertifikasi->asesi->nama_lengkap ?? '-';
        
        // Fix 3: Date and Time Logic
        // Use 'tanggal_pelaksanaan' from Jadwal accessor
        $tanggal = $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') : '-';
        
        // Use 'waktu_mulai' and 'waktu_selesai'
        $start = $sertifikasi->jadwal->waktu_mulai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_mulai)->format('H:i') : null;
        $end = $sertifikasi->jadwal->waktu_selesai ? \Carbon\Carbon::parse($sertifikasi->jadwal->waktu_selesai)->format('H:i') : null;
        
        if ($start && $end) {
            $waktu = "$start - $end WIB";
        } elseif ($start) {
            $waktu = "$start WIB";
        } else {
            $waktu = '-';
        }
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
            {{-- Note: Usually TUK Type (Sewaktu/Tempat Kerja) is stored in 'jenisTuk' or implied by context. 
                 Since current logic passes a String name, we need to defer to the specific ID or type.
                 Assuming $sertifikasi->jadwal->id_jenis_tuk or distinct relation.
                 For now, let's keep it simple: Just show the NAME string if we can't determine type easily, 
                 OR default to 'Sewaktu' if no logic. 
                 However, to not break UI, we just display the NAME in text if type logic is complex, 
                 but clearer is to replicate the radio.
                 Let's try to map the name: --}}
            <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-gray-400" 
                   @checked(stripos($tuk, 'sewaktu') !== false || $tuk == 'Sewaktu') disabled>
            <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
        </div>
        <div class="flex items-center space-x-2">
            <input type="radio" id="tuk_tempatkerja" name="tuk_type" class="form-radio h-4 w-4 text-gray-400"
                   @checked(stripos($tuk, 'kerja') !== false || $tuk != 'Sewaktu') disabled>
            <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
        </div>
        <!-- Explicit Name Display if needed -->
        <span class="text-sm font-semibold text-gray-700 ml-2">({{ $tuk }})</span>
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