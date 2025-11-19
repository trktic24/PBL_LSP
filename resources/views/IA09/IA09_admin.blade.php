@extends('layouts.app-profil')

@section('content')

<?php
// --- PERBAIKAN UTAMA: DEKODE JSON DAHULU ---
// Memastikan variabel loop selalu berupa array,
// baik itu hasil casting Eloquent (array) atau string JSON mentah, atau array kosong.
$questions = isset($data->questions) && is_string($data->questions) 
    ? json_decode($data->questions, true) 
    : (is_array($data->questions) ? $data->questions : []);

$units = isset($data->units) && is_string($data->units) 
    ? json_decode($data->units, true) 
    : (is_array($data->units) ? $data->units : []);

// Untuk data Penyusun dan Validator (jika disimpan sebagai JSON di kolom terpisah)
// Asumsi ini adalah object/array, jika tidak ada, berikan object kosong untuk mencegah error
$penyusun = $data->penyusun ?? (object)[]; 
$validator = $data->validator ?? (object)[]; 
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-6">
            FR.IA.09. PERTANYAAN WAWANCARA
        </h1>
        <p class="text-center text-sm text-gray-500 mb-8">Tampilan Arsip Admin</p>

        <!-- Skema & Data Umum -->
        <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-gray-50">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Skema & Data Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-2">
                    <p><strong>Skema Sertifikasi</strong>: <span class="font-normal text-gray-700">{{ $data->skema->judul ?? '...' }}</span></p>
                    <p><strong>Nomor Skema</strong>: <span class="font-normal text-gray-700">{{ $data->skema->nomor ?? '...' }}</span></p>
                    <p><strong>Tanggal Asesmen</strong>: <span class="font-normal text-gray-700">{{ ($data->tanggal_asesmen ?? null) ? \Carbon\Carbon::parse($data->tanggal_asesmen)->format('d F Y') : '...' }}</span></p>
                </div>
                <div class="space-y-2">
                    <p><strong>Nama Asesor</strong>: <span class="font-normal text-gray-700">{{ $data->asesor->nama_lengkap ?? '...' }}</span></p>
                    <p><strong>Nama Asesi</strong>: <span class="font-normal text-gray-700">{{ $data->asesi->nama_lengkap ?? '...' }}</span></p>
                    <p><strong>TUK</strong>: <span class="font-normal text-gray-700">{{ $data->tuk ?? '...' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Daftar Pertanyaan Wawancara -->
        <h2 class="text-xl font-semibold mb-4 text-blue-700">Daftar Pertanyaan Wawancara</h2>
        <div class="overflow-x-auto mb-6 shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">Daftar Pertanyaan Wawancara</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-5/12">Kesimpulan Jawaban Asesi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">Pencapaian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Loop data pertanyaan wawancara MENGGUNAKAN $questions --}}
                    @forelse($questions as $index => $q)
                        <tr class="{{ ($q['pencapaian'] ?? '') === 'Ya' ? 'bg-green-50' : (($q['pencapaian'] ?? '') === 'Tidak' ? 'bg-red-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                Sesuai dengan bukti no. <strong>{{ $q['no_bukti'] ?? '...' }}</strong> yang Anda ajukan, <br>{{ $q['pertanyaan'] ?? '...' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 italic">
                                {{ $q['kesimpulan_jawaban'] ?? 'Belum diisi' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                <span class="inline-block px-3 py-1 rounded-full text-white {{ ($q['pencapaian'] ?? '') === 'Ya' ? 'bg-green-600' : (($q['pencapaian'] ?? '') === 'Tidak' ? 'bg-red-600' : 'bg-gray-400') }}">
                                    {{ $q['pencapaian'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pertanyaan wawancara yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Tabel Rekomendasi Unit Kompetensi -->
        <h2 class="text-xl font-semibold mb-4 text-blue-700">Rekomendasi Unit Kompetensi</h2>
        <div class="overflow-x-auto mb-6 shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Kode Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-7/12">Judul Unit Kompetensi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $index => $u)
                        <tr class="{{ ($u['rekomendasi'] ?? '') === 'K' ? 'bg-green-50' : (($u['rekomendasi'] ?? '') === 'BK' ? 'bg-red-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}.</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $u['kode'] ?? '...' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $u['unit'] ?? '...' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                <span class="inline-block px-3 py-1 rounded-full text-white {{ ($u['rekomendasi'] ?? '') === 'K' ? 'bg-green-600' : (($u['rekomendasi'] ?? '') === 'BK' ? 'bg-red-600' : 'bg-gray-400') }}">
                                    {{ $u['rekomendasi'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data unit kompetensi yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Area Catatan dan Keputusan Asesor -->
        <div class="border border-gray-300 p-4 rounded-lg mb-6 bg-yellow-50">
            <h2 class="text-xl font-semibold mb-4 text-red-700">Keputusan & Catatan Asesor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="mb-2"><strong>Rekomendasi Umum Asesor:</strong></p>
                    <span class="inline-block px-4 py-2 rounded-full text-lg font-bold text-white shadow-lg {{ ($data->rekomendasi_asesor ?? '') === 'K' ? 'bg-green-700' : (($data->rekomendasi_asesor ?? '') === 'BK' ? 'bg-red-700' : 'bg-gray-500') }}">
                        {{ ($data->rekomendasi_asesor ?? '') === 'K' ? 'KOMPETEN' : (($data->rekomendasi_asesor ?? '') === 'BK' ? 'BELUM KOMPETEN' : 'BELUM DIPUTUSKAN') }}
                    </span>
                </div>
                <div>
                    <p class="mb-2"><strong>Catatan Tambahan Asesor:</strong></p>
                    <div class="p-3 border border-gray-400 bg-white rounded h-24 overflow-y-auto italic text-gray-700">
                        {{ $data->catatan_asesor ?? 'Tidak ada catatan khusus dari Asesor.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Tanda Tangan Asesi dan Asesor -->
        <h2 class="text-xl font-semibold mt-10 mb-4 text-blue-700">Tanda Tangan Asesi dan Asesor</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-2">Asesi</h3>
                <div class="border border-gray-300 p-4 rounded-lg bg-gray-50">
                    <p class="mb-1"><strong>Nama</strong>: {{ $data->asesi->nama_lengkap ?? '...' }}</p>
                    <p class="mb-4"><strong>Tanggal TTD</strong>: {{ $data->tgl_ttd_asesi ?? '...' }}</p>
                    <div class="h-24 border border-dashed border-gray-400 flex items-center justify-center text-gray-500">
                        <p class="text-sm">TTD Terlampir / Digital</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Asesor</h3>
                <div class="border border-gray-300 p-4 rounded-lg bg-gray-50">
                    <p class="mb-1"><strong>Nama</strong>: {{ $data->asesor->nama_lengkap ?? '...' }}</p>
                    <p class="mb-1"><strong>No. Reg. MET.</strong>: {{ $data->asesor->no_reg_met ?? '...' }}</p>
                    <p class="mb-4"><strong>Tanggal TTD</strong>: {{ $data->tgl_ttd_asesor ?? '...' }}</p>
                    <div class="h-24 border border-dashed border-gray-400 flex items-center justify-center text-gray-500">
                        <p class="text-sm">TTD Terlampir / Digital</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Area Penyusun dan Validator (Tambahan Admin) -->
        <h2 class="text-xl font-semibold mt-10 mb-4 text-blue-700">Verifikasi Dokumen (Penyusun dan Validator)</h2>
        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">STATUS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">NAMA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">NOMOR MET</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">TANDA TANGAN & TANGGAL</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Data Penyusun --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PENYUSUN</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $penyusun->nama_1 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $penyusun->met_1 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $penyusun->ttd_1 ?? '...' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PENYUSUN</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $penyusun->nama_2 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $penyusun->met_2 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $penyusun->ttd_2 ?? '...' }}</td>
                    </tr>
                    {{-- Data Validator --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">VALIDATOR</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $validator->nama_1 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $validator->met_1 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $validator->ttd_1 ?? '...' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">VALIDATOR</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $validator->nama_2 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $validator->met_2 ?? '...' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $validator->ttd_2 ?? '...' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tombol Aksi -->
        <div class="text-center mt-8 space-x-4">
            <button class="bg-indigo-600 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Data Dokumen
            </button>
            <button class="bg-green-600 text-white font-bold px-8 py-3 rounded-lg shadow-md hover:bg-green-700 transition">
                <i class="fas fa-file-pdf mr-2"></i> Ekspor PDF
            </button>
        </div>

    </div>
</div>

@endsection