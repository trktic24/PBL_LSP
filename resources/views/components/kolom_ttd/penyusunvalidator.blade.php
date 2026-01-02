@props(['sertifikasi' => null])

@php
    // Ambil data dari $sertifikasi
    $penyusunValidator = $sertifikasi->penyusunValidator ?? null;
    $penyusun = $penyusunValidator->penyusun ?? null;
    $validator = $penyusunValidator->validator ?? null;

    // Data Penyusun
    $namaPenyusun = $penyusun->penyusun ?? '-';
    $noMetPenyusun = $penyusun->no_MET_penyusun ?? '-';
    $ttdPenyusun = $penyusun->ttd ?? null;

    // Data Validator
    $namaValidator = $validator->nama_validator ?? '-';
    $noMetValidator = $validator->no_MET_validator ?? '-';
    $ttdValidator = $validator->ttd ?? null;

    // Tanggal Validasi
    $tanggal = $penyusunValidator && $penyusunValidator->tanggal_validasi 
        ? \Carbon\Carbon::parse($penyusunValidator->tanggal_validasi)->format('d-m-Y') 
        : '-';
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
                    @if($ttdPenyusun)
                        <img src="{{ route('secure.file', ['path' => $ttdPenyusun]) }}" alt="Tanda Tangan Penyusun" class="h-full object-contain">
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
                    @if($ttdValidator)
                        <img src="{{ route('secure.file', ['path' => $ttdValidator]) }}" alt="Tanda Tangan Validator" class="h-full object-contain">
                    @else
                        <div class="border-b border-gray-300 h-full w-32 flex items-end text-xs text-gray-400 pb-1">Belum ada TTD</div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>