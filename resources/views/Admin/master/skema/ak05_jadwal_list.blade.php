@extends('layouts.app-sidebar-skema', [
    'jadwal' => (object)['skema' => $skema],
    'backUrl' => route('admin.skema.detail', $skema->id_skema)
])

@section('title', 'Daftar Jadwal AK.05')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full my-4">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Daftar Jadwal & Asesor</h1>
            <p class="text-blue-600 font-semibold uppercase tracking-wider text-sm mt-1">
                {{ $skema->nomor_skema }} - FR.AK.05 (Laporan Asesmen)
            </p>
        </div>
        <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-xl transition text-sm font-medium border border-gray-200 shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Detail Skema
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Asesor Penguji</th>
                    <th class="px-6 py-4">TUK / Lokasi</th>
                    <th class="px-6 py-4">Waktu Pelaksanaan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($jadwalList as $index => $jadwal)
                <tr class="hover:bg-blue-50/40 transition group">
                    <td class="px-6 py-6 text-sm font-medium text-gray-400 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ substr($jadwal->asesor->nama_lengkap ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 line-clamp-1">{{ $jadwal->asesor->nama_lengkap ?? 'Asesor Belum Ditentukan' }}</h4>
                                <p class="text-xs text-gray-400 font-medium tracking-tight mt-0.5">{{ $jadwal->asesor->no_reg ?? 'MET.000.000XXX' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6 font-medium text-gray-600">
                        <div class="flex items-center gap-2">
                             <i class="fas fa-map-marker-alt text-gray-300 text-xs"></i>
                             <span>{{ $jadwal->masterTuk->nama_lokasi ?? 'Lokasi Belum Diatur' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->isoFormat('D MMMM Y') }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $jadwal->waktu_mulai ?? '08:00' }} WIB</span>
                        </div>
                    </td>
                    <td class="px-6 py-6 text-center">
                        <a href="{{ route('asesor.ak05', $jadwal->id_jadwal) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm hover:shadow-blue-200 transform hover:scale-105 active:scale-95 whitespace-nowrap">
                            <i class="fas fa-file-alt"></i> LIHAT LAPORAN
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-24 text-center">
                        <div class="flex flex-col items-center opacity-20">
                            <i class="fas fa-calendar-times text-6xl mb-4"></i>
                            <p class="text-xl font-bold tracking-tight">Belum ada Jadwal / Asesor Terdaftar untuk skema ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
