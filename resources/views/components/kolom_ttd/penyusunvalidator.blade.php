@props(['sertifikasi' => null])

@php
    // Ambil data dari $sertifikasi
    if (!$sertifikasi) {
        return;
    }
    $penyusunValidator = $sertifikasi->penyusunValidator ?? null;
    $penyusun = $penyusunValidator->penyusun ?? null;
    $validator = $penyusunValidator->validator ?? null;

    // Data Penyusun (Fallback to Asesor if missing)
    if ($penyusun) {
        $namaPenyusun = $penyusun->penyusun ?? '-';
        $noMetPenyusun = $penyusun->no_MET_penyusun ?? '-';
        $ttdPenyusun = $penyusun->ttd ?? null;
    } else {
        // FALLBACK: Use Asesor Data
        $namaPenyusun = $sertifikasi->jadwal->asesor->nama_lengkap ?? '-';
        $noMetPenyusun = $sertifikasi->jadwal->asesor->nomor_regis ?? '-';
        // Check if Asesor has signature
        $ttdPenyusun = $sertifikasi->jadwal->asesor->tanda_tangan ?? null;
    }

    // Data Validator
    // Data Validator (fallback to Asesor if missing)
        if ($validator) {
            $namaValidator = $validator->nama_validator ?? '-';
            $noMetValidator = $validator->no_MET_validator ?? '-';
            $ttdValidator = $validator->ttd ?? null;
        } else {
            // Fallback: use Asesor data as validator
            $namaValidator = $sertifikasi->jadwal && $sertifikasi->jadwal->asesor ? $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' : '-';
            $noMetValidator = $sertifikasi->jadwal && $sertifikasi->jadwal->asesor ? $sertifikasi->jadwal->asesor->nomor_regis ?? '-' : '-';
            $ttdValidator = $sertifikasi->jadwal && $sertifikasi->jadwal->asesor ? $sertifikasi->jadwal->asesor->tanda_tangan ?? null : null;
        }

    // Tanggal Validasi
    // Tanggal Validasi (fallback to Jadwal tanggal_pelaksanaan)
        if ($penyusunValidator && $penyusunValidator->tanggal_validasi) {
            $tanggal = \Carbon\Carbon::parse($penyusunValidator->tanggal_validasi)->format('d-m-Y');
        } else {
            $tanggal = $sertifikasi->jadwal && $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->format('d-m-Y') : '-';
        }

    // --- LOGIKA TANDA TANGAN BASE64 ---
    $ttdPenyusunBase64 = null;
    if ($ttdPenyusun) {
        // Coba path langsung dulu, lalu fallback
        $pathsToTry = [
            storage_path('app/private_uploads/' . $ttdPenyusun),
            storage_path('app/public/' . $ttdPenyusun),
        ];
        foreach ($pathsToTry as $path) {
            if (file_exists($path)) {
                $ttdPenyusunBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                break;
            }
        }
    }

    $ttdValidatorBase64 = null;
    if ($ttdValidator) {
        $pathsToTry = [
            storage_path('app/private_uploads/' . $ttdValidator),
            storage_path('app/public/' . $ttdValidator),
        ];
        foreach ($pathsToTry as $path) {
            if (file_exists($path)) {
                $ttdValidatorBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                break;
            }
        }
    }
@endphp

<div class="border border-gray-200 shadow-md p-4 rounded-lg bg-white mt-8">
    <h3 class="text-lg font-semibold mb-4">Pencatatan dan Validasi</h3>

    <div class="flex flex-col gap-4">

        {{-- BAGIAN PENYUSUN --}}
        <div>
            <h4 class="font-medium mb-2 text-base text-gray-800">Penyusun</h4>
            <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm">
                <div>Nama</div>
                <div>:</div>
                <div class="min-h-[1.5em] font-medium text-gray-900">{{ $namaPenyusun }}</div>

                <div>No. Reg. MET.</div>
                <div>:</div>
                <div class="min-h-[1.5em] text-gray-900">{{ $noMetPenyusun }}</div>

                <div>Tanggal</div>
                <div>:</div>
                <div class="min-h-[1.5em] text-gray-900">{{ $tanggal }}</div>

                <div>Tanda Tangan</div>
                <div>:</div>
                <div class="h-16">
                    @if($ttdPenyusunBase64)
                        <img src="{{ $ttdPenyusunBase64 }}" alt="Tanda Tangan Penyusun" class="h-full object-contain">
                    @else
                        <div class="border-b border-gray-300 h-full w-32 flex items-end text-xs text-gray-400 pb-1">Belum ada TTD</div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="border-gray-200">

        {{-- BAGIAN VALIDATOR --}}
        <div>
            <h4 class="font-medium mb-2 text-base text-gray-800">Validator</h4>
            <div class="grid grid-cols-[auto_auto_1fr] gap-x-2 gap-y-1 text-sm">
                <div>Nama</div>
                <div>:</div>
                <div class="min-h-[1.5em] font-medium text-gray-900">{{ $namaValidator }}</div>

                <div>No. Reg. MET.</div>
                <div>:</div>
                <div class="min-h-[1.5em] text-gray-900">{{ $noMetValidator }}</div>

                <div>Tanggal</div>
                <div>:</div>
                <div class="min-h-[1.5em] text-gray-900">{{ $tanggal }}</div>

                <div>Tanda Tangan</div>
                <div>:</div>
                <div class="h-16">
                    @if($ttdValidatorBase64)
                        <img src="{{ $ttdValidatorBase64 }}" alt="Tanda Tangan Validator" class="h-full object-contain">
                    @else
                        <div class="border-b border-gray-300 h-full w-32 flex items-end text-xs text-gray-400 pb-1">Belum ada TTD</div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>