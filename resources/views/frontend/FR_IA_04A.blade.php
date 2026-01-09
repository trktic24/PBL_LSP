@extends($layout ?? 'layouts.wizard')

@section('wizard-content')
    {{-- Style khusus untuk halaman ini --}}
    <style>
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    {{-- KONTEN UTAMA --}}
    <div class="bg-white p-6">
        
        {{-- Judul & Kop --}}
        <div class="mb-8 text-center"> 
            <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
            
            <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-6 inline-block">
                FR.IA.04A. DIT – DAFTAR INSTRUKSI TERSTRUKTUR
            </h1>
            @if(isset($isMasterView))
                <p class="text-blue-600 font-bold mt-2">[TEMPLATE MASTER]</p>
            @endif
        </div>

        {{-- Informasi Data Peserta --}}
        <div class="grid grid-cols-[200px_auto] gap-y-3 text-sm mb-10 text-gray-700">
            <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</div>
            <div>
                <div class="flex gap-2"><span class="font-semibold w-20">Judul</span> : {{ $skema->nama_skema ?? 'Junior Web Programmer' }}</div>
                <div class="flex gap-2"><span class="font-semibold w-20">Nomor</span> : {{ $skema->nomor_skema ?? '-' }}</div>
            </div>

            @if(isset($isMasterView))
                {{-- Tampilkan Daftar Unit untuk Master View --}}
                <div class="font-bold text-black">Unit Kompetensi</div>
                <div>
                    <ul class="list-disc pl-5">
                        @forelse($units ?? [] as $unit)
                            <li>{{ $unit->kode_unit }} - {{ $unit->judul_unit }}</li>
                        @empty
                            <li>-</li>
                        @endforelse
                    </ul>
                </div>
            @endif

            @if(!isset($isMasterView))
            <div class="font-bold text-black">TUK</div>
            <div>: Sewaktu / Tempat Kerja / <span class="font-bold">Mandiri</span></div>

            <div class="font-bold text-black">Nama Asesor</div>
            <div>: -</div>

            <div class="font-bold text-black">Nama Asesi</div>
            <div>: Tatang Sidartang</div>

            <div class="font-bold text-black">Tanggal</div>
            <div>: 18 November 2025</div>
            @endif
        </div>

        {{-- Panduan Asesor --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-sm text-gray-700">
            <h3 class="font-bold text-blue-900 mb-2">PANDUAN BAGI ASESOR:</h3>
            <ul class="list-disc pl-5 space-y-1">
                <li>Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</li>
                <li>Proyek singkat dibuat untuk keseluruhan unit kompetensi atau masing-masing kelompok pekerjaan.</li>
                <li>Kumpulkan hasil proyek sesuai keluaran yang ditetapkan.</li>
            </ul>
        </div>

        {{-- Form Input --}}
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-4">Instruksi & Skenario</h3>
            
            <table class="w-full text-sm form-table mb-8">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left w-1/3 align-top">Instruksi</th>
                        <th class="p-3 text-left w-2/3 align-top">Detail Skenario & Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-4 align-top font-semibold bg-gray-50">
                            Hal yang harus disiapkan atau dilakukan atau dihasilkan untuk suatu proyek singkat/kegiatan terstruktur
                        </td>
                        <td class="p-4 align-top">
                            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Skenario Studi Kasus:</label>
                            <textarea class="w-full border border-gray-300 rounded p-3 text-gray-700 focus:outline-none focus:border-blue-500" rows="5" placeholder="Tuliskan skenario kasus di sini..."></textarea>
                            
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <span class="font-bold text-xs">Waktu Pengerjaan:</span>
                                <input type="text" class="border-b border-gray-400 w-20 text-center focus:outline-none" placeholder="..."> 
                                <span class="text-xs">Menit</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4 align-top font-semibold bg-gray-50">
                            Hal yang perlu didemonstrasikan
                        </td>
                        <td class="p-4 align-top">
                            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Hasil yang diharapkan:</label>
                            <textarea class="w-full border border-gray-300 rounded p-3 text-gray-700 focus:outline-none focus:border-blue-500" rows="5" placeholder="Tuliskan output yang diharapkan..."></textarea>
                            
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <span class="font-bold text-xs">Waktu Demonstrasi:</span>
                                <input type="text" class="border-b border-gray-400 w-20 text-center focus:outline-none" placeholder="..."> 
                                <span class="text-xs">Menit</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Validasi --}}
        <div class="border border-gray-300 rounded-lg p-6 mt-8">
            <h4 class="font-bold text-md mb-6 uppercase tracking-wide">Validasi & Tanda Tangan</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-sm">
                <div>
                    <p class="mb-12 font-semibold">Tanda Tangan Asesi</p>
                    <div class="h-20 border-b border-gray-300 mb-2"></div>
                    <p class="font-bold">Tatang Sidartang</p>
                </div>
                <div>
                    <p class="mb-12 font-semibold">Tanda Tangan Asesor</p>
                    <div class="h-20 border-b border-gray-300 mb-2"></div>
                    <p class="font-bold">(Nama Asesor)</p>
                </div>
                <div>
                    <p class="mb-12 font-semibold">Supervisor (Jika ada)</p>
                    <div class="h-20 border-b border-gray-300 mb-2"></div>
                    <p class="font-bold text-gray-400">-</p>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        @if(!isset($isMasterView))
        <div class="mt-10 flex justify-end gap-4">
            <button class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded shadow hover:bg-gray-300 transition">Simpan Draft</button>
            <button class="px-6 py-2 bg-blue-600 text-white font-bold rounded shadow hover:bg-blue-700 transition">Submit Form</button>
        </div>
        @else
        <div class="mt-10 flex justify-end gap-4">
             <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-600 text-white font-bold rounded shadow hover:bg-gray-700 transition">Kembali</a>
        </div>
        @endif

    </div>
@endsection