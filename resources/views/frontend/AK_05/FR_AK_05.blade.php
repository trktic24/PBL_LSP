@extends('layouts.app-sidebar-skema')

{{-- =======================================================================
     BAGIAN 1: SIDEBAR KHUSUS ASESOR (MENIMPA SIDEBAR DEFAULT)
     ======================================================================= --}}
@section('sidebar')
    <div class="w-full h-full min-h-screen bg-gradient-to-b from-blue-600 to-blue-400 text-white p-8 flex flex-col items-center text-center">
        
        {{-- Tombol Kembali --}}
        <div class="w-full flex justify-start mb-6">
            <a href="{{ url()->previous() }}" class="flex items-center text-white/80 hover:text-white transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-medium">Kembali</span>
            </a>
        </div>

        {{-- Logo --}}
        <div class="bg-white p-4 rounded-full w-32 h-32 flex items-center justify-center mb-6 shadow-lg">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Logo_Politeknik_Negeri_Semarang.png/1200px-Logo_Politeknik_Negeri_Semarang.png" 
                 alt="Logo Polines" class="w-24 h-auto">
        </div>

        {{-- Judul Skema --}}
        <h2 class="text-2xl font-bold mb-3 leading-tight">
            {{ $jadwal->skema->nama_skema ?? $jadwal->skema->judul_skema ?? 'Judul Skema' }}
        </h2>

        <p class="text-sm text-blue-100 mb-8 leading-relaxed">
            Laporan Asesmen (FR.AK.05)
        </p>

        {{-- Identitas Asesor --}}
        <div class="mb-8">
            <h3 class="text-blue-200 font-bold tracking-widest uppercase text-sm mb-4">ASESOR PENGUJI:</h3>
            
            <div class="flex flex-col items-center">
                @if($asesor->profil_picture)
                    <img src="{{ asset('storage/'.$asesor->profil_picture) }}" class="w-24 h-24 rounded-full border-4 border-white/30 object-cover mb-3 shadow-md">
                @else
                    <div class="w-24 h-24 rounded-full border-4 border-white/30 bg-white/20 flex items-center justify-center mb-3 shadow-md text-3xl font-bold">
                        {{ substr($asesor->nama_lengkap, 0, 1) }}
                    </div>
                @endif
                
                <h4 class="text-xl font-bold">{{ $asesor->nama_lengkap }}</h4>
                <p class="text-sm text-blue-100">{{ $asesor->no_reg ?? 'No. Reg: -' }}</p>
            </div>
        </div>

        {{-- Info Waktu --}}
        <div class="mt-auto mb-8">
            <h3 class="text-blue-200 font-bold tracking-widest uppercase text-sm mb-2">TANGGAL:</h3>
            <p class="text-xl font-bold">
                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->isoFormat('D MMMM Y') }}
            </p>
        </div>
    </div>
@endsection


{{-- =======================================================================
     BAGIAN 2: KONTEN UTAMA FORM FR.AK.05
     ======================================================================= --}}
@section('content')
    <style>
        .custom-checkbox:checked { background-color: #2563eb; border-color: #2563eb; }
        textarea:focus, input:focus { outline: none; --tw-ring-color: #3b82f6; }
    </style>

    <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">

        <x-header_form.header_form title="FR.AK.05. LAPORAN ASESMEN" /><br>

        {{-- Notifikasi Sukses/Gagal --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Wrapper --}}
        <form action="{{ route('ak05.store', $jadwal->id_jadwal) }}" method="POST">
            @csrf

            {{-- 1. IDENTITAS SKEMA --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-6 gap-y-4 gap-x-4 text-sm">
                    {{-- Judul Skema (Diperbaiki: nama_skema) --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">Skema Sertifikasi</dt>
                    <dd class="col-span-1 md:col-span-4 text-gray-900 font-semibold">
                        : {{ $jadwal->skema->nama_skema ?? $jadwal->skema->judul_skema ?? '-' }}
                    </dd>

                    {{-- Nomor Skema --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">Nomor Skema</dt>
                    <dd class="col-span-1 md:col-span-4 text-gray-900 font-semibold">
                        : {{ $jadwal->skema->nomor_skema ?? '-' }}
                    </dd>

                    {{-- TUK (Diperbaiki: Pengecekan Case Insensitive) --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">TUK</dt>
                    <dd class="col-span-1 md:col-span-4 flex items-center gap-4 text-gray-900">
                        <span>:</span>
                        @php $tuk = strtolower($jadwal->jenis_tuk ?? ''); @endphp
                        
                        <label class="flex items-center gap-2 cursor-not-allowed opacity-75">
                            <input type="radio" disabled {{ $tuk == 'sewaktu' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"> Sewaktu
                        </label>
                        <label class="flex items-center gap-2 cursor-not-allowed opacity-75">
                            <input type="radio" disabled {{ $tuk == 'tempat kerja' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"> Tempat Kerja
                        </label>
                        <label class="flex items-center gap-2 cursor-not-allowed opacity-75">
                            <input type="radio" disabled {{ $tuk == 'mandiri' ? 'checked' : '' }} class="w-4 h-4 text-blue-600"> Mandiri
                        </label>
                    </dd>

                    {{-- Nama Asesor --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">Nama Asesor</dt>
                    <dd class="col-span-1 md:col-span-4 text-gray-900 font-bold">
                        : {{ $asesor->nama_lengkap }}
                    </dd>

                    {{-- Nama Asesi --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">Nama Asesi</dt>
                    <dd class="col-span-1 md:col-span-4 text-gray-900 text-sm italic">
                        : Terlampir pada tabel di bawah
                    </dd>

                    {{-- Tanggal --}}
                    <dt class="col-span-1 md:col-span-2 font-medium text-gray-500">Tanggal</dt>
                    <dd class="col-span-1 md:col-span-4 text-gray-900">
                        : {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->isoFormat('D MMMM Y') }}
                    </dd>
                </dl>
            </div>

            {{-- 2. TABEL PESERTA / ASESI --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Data Asesi & Rekomendasi</h3>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th rowspan="2" class="px-4 py-4 text-center w-12 border-r border-gray-700">No.</th>
                                <th rowspan="2" class="px-4 py-4 text-left border-r border-gray-700">Nama Asesi</th>
                                <th colspan="2" class="px-4 py-2 text-center border-b border-gray-700 border-r">Rekomendasi</th>
                                <th rowspan="2" class="px-4 py-4 text-left w-[35%]">Keterangan</th>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-center w-16 bg-gray-800 border-r border-gray-700">K</th>
                                <th class="px-4 py-2 text-center w-16 bg-gray-800 border-r border-gray-700">BK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($listAsesi as $index => $data)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3 text-center font-bold text-gray-700 border-r border-gray-200">{{ $loop->iteration }}.</td>
                                    
                                    {{-- Nama Asesi --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <input type="text" value="{{ $data->asesi->nama_lengkap ?? 'Tanpa Nama' }}"
                                            class="block w-full text-sm border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed font-medium text-gray-700" readonly>
                                        <input type="hidden" name="asesi[{{ $index }}][id_asesi]" value="{{ $data->asesi->id_asesi }}">
                                    </td>

                                    {{-- Checkbox K (Kompeten) --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="asesi[{{ $index }}][rekomendasi]" value="K"
                                            {{ $data->rekomendasi_AK05 == 'K' ? 'checked' : '' }}
                                            class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                    </td>

                                    {{-- Checkbox BK (Belum Kompeten) --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="asesi[{{ $index }}][rekomendasi]" value="BK"
                                            {{ $data->rekomendasi_AK05 == 'BK' ? 'checked' : '' }}
                                            class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-4 py-3">
                                        <input type="text" name="asesi[{{ $index }}][keterangan]"
                                            value="{{ $data->keterangan_AK05 }}"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            placeholder="Keterangan...">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 italic">
                                        Belum ada peserta yang terdaftar di jadwal ini.
                                    </td>
                                </tr>    
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. ASPEK & CATATAN --}}
            @php $firstData = $listAsesi->first(); @endphp
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Aspek Negatif dan Positif dalam Asesmen</h3>
                    <textarea name="aspek_asesmen" rows="4" class="block w-full text-sm border-gray-300 rounded-lg p-3" placeholder="Tuliskan aspek positif dan negatif...">{{ $firstData->aspek_dalam_AK05 ?? '' }}</textarea>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Pencatatan Penolakan Hasil Asesmen</h3>
                    <textarea name="catatan_penolakan" rows="3" class="block w-full text-sm border-gray-300 rounded-lg p-3" placeholder="Jika ada penolakan, tuliskan disini...">{{ $firstData->catatan_penolakan_AK05 ?? '' }}</textarea>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Saran Perbaikan (Asesor/Personil Terkait)</h3>
                    <textarea name="saran_perbaikan" rows="3" class="block w-full text-sm border-gray-300 rounded-lg p-3" placeholder="Saran perbaikan untuk proses berikutnya...">{{ $firstData->saran_dan_perbaikan_AK05 ?? '' }}</textarea>
                </div>
            </div>

            {{-- 4. PENGESAHAN --}}
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-6 shadow-lg mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Pengesahan Laporan</h3>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan:</label>
                    <textarea name="catatan_akhir" rows="2" class="block w-full text-sm border-gray-300 rounded-lg p-2" placeholder="Catatan akhir...">{{ $firstData->catatan_AK05 ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Tanda Tangan Asesor</label>
                        <div class="w-full h-40 bg-gray-50 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center relative group">
                            @if($asesor && $asesor->tanda_tangan)
                                <img src="{{ asset('storage/'.$asesor->tanda_tangan) }}" class="h-32 object-contain">
                            @else
                                <div class="text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <p class="text-sm">Tanda tangan digital</p>
                                    <p class="text-xs">(Otomatis dari Profil)</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 space-y-3">
                            <div>
                                <label class="text-xs text-gray-500 font-semibold uppercase">Nama</label>
                                <input type="text" class="w-full text-sm font-bold border-0 border-b-2 bg-transparent" value="{{ $asesor->nama_lengkap ?? '' }}" readonly>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold uppercase">No. Reg. MET</label>
                                <input type="text" class="w-full text-sm font-bold border-0 border-b-2 bg-transparent" value="{{ $asesor->nomor_regis ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-between mt-8 mb-8 pb-10">
                <a href="{{ url()->previous() }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 shadow-sm">Kembali</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg transform hover:-translate-y-0.5 transition">Simpan Laporan FR.AK.05</button>
            </div>

        </form>
    </div>
@endsection