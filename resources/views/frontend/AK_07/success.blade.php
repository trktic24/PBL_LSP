@extends('layouts.app-sidebar')

@section('content')
<div class="p-4 sm:p-6 md:p-8 font-[Poppins] flex flex-col items-center justify-center min-h-[60vh]">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-md w-full text-center transform transition-all hover:scale-[1.01]">

        {{-- Success Icon --}}
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
            <i class="fas fa-check text-green-600 text-3xl"></i>
        </div>

        {{-- Message --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Berhasil Disimpan!</h2>
        <p class="text-gray-600 mb-8 leading-relaxed">
            Formulir <strong>FR.AK.07. Ceklis Penyesuaian yang Wajar dan Beralasan</strong> telah berhasil disimpan ke dalam sistem.
        </p>

        {{-- Action Button --}}
        @php
        $role = Auth::user()->role->nama_role;
        $backUrl = '#';
        if ($role === 'asesor') {
        $backUrl = route('asesor.daftar_asesi', $sertifikasi->id_jadwal);
        } elseif ($role === 'asesi') {
        $backUrl = route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]);
        } else {
        $backUrl = url()->previous();
        }
        @endphp

        <a href="{{ $backUrl }}" class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg transition-all duration-200 group">
            <i class="fas fa-calendar-alt mr-2"></i>
            Kembali ke Menu Jadwal
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>

        {{-- Secondary Action --}}
        <div class="mt-4">
            <a href="{{ route('fr-ak-07.create', $sertifikasi->id_data_sertifikasi_asesi) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Lihat Kembali Form AK.07
            </a>
        </div>
    </div>
</div>
@endsection