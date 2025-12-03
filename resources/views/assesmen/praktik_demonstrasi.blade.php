@use('Carbon\Carbon')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.02 - Tugas Praktik Demonstrasi</title>
    {{-- Memuat Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- WAJIB: Tambahkan Meta CSRF Token di HEAD --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Mengatur font agar lebih rapih */
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        /* Styling untuk textarea/div tampilan skenario */
        .whitespace-pre-line {
            white-space: pre-line;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="sticky top-0 h-screen">
                {{-- Pastikan data seperti $asesor dan $idAsesi dikirim dari controller --}}
                <x-sidebar2 backUrl="/tracker" :asesorNama="$asesor['nama']" :asesorNoReg="$asesor['no_reg']" :idAsesi="$idAsesi" />
            </div>
            {{-- Notifikasi (Disesuaikan agar berdiri sendiri) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded shadow-sm">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="ml-2">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded shadow-sm">
                    <strong class="font-bold">Error!</strong>
                    <span class="ml-2">{{ session('error') }}</span>
                </div>
            @endif

            @if ($sertifikasi)
                <form action="{{ route('ia02.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                    @csrf

                    {{-- HEADER (Ganti x-header_form dengan div statis) --}}
                    <div class="mb-8 p-6 bg-white border-t-4 border-gray-800 rounded-lg shadow-lg">
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">FR.IA.02 - TUGAS PRAKTIK DEMONSTRASI</h1>
                        <p class="text-gray-600">Dokumen ini digunakan oleh Asesor untuk memberikan instruksi kepada
                            Asesi.
                        </p>
                    </div>


                    {{-- IDENTITAS SKEMA (Ganti x-identitas_skema_form dengan div statis) --}}
                    <div class="mb-10 p-6 bg-white rounded-lg shadow-lg border-2 border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">I. Identitas Skema & Asesi</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            {{-- Kolom Kiri --}}
                            <div>
                                <p class="mb-1"><strong>Judul Skema:</strong></p>
                                <p class="p-2 bg-gray-50 border rounded">
                                    {{ $sertifikasi->skema_sertifikasi->judul_skema ?? '-' }}</p>

                                <p class="mt-3 mb-1"><strong>Nomor Skema:</strong></p>
                                <p class="p-2 bg-gray-50 border rounded">
                                    {{ $sertifikasi->skema_sertifikasi->nomor_skema ?? '-' }}</p>
                            </div>
                            {{-- Kolom Kanan --}}
                            <div>
                                <p class="mb-1"><strong>Nama Asesi:</strong></p>
                                <p class="p-2 bg-gray-50 border rounded">{{ $sertifikasi->asesi->nama ?? '-' }}</p>

                                <p class="mt-3 mb-1"><strong>Tanggal Asesmen:</strong></p>
                                <p class="p-2 bg-gray-50 border rounded">
                                    {{ $sertifikasi->jadwal->tanggal_pelaksanaan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>


                    {{-- PETUNJUK --}}
                    <div class="bg-blue-50 border border-blue-300 p-6 mb-10 rounded shadow-sm">
                        <h2 class="text-lg font-bold text-blue-800 mb-3">A. Petunjuk</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
                            <li>Baca instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek.</li>
                            <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas.</li>
                            <li>Laksanakan pekerjaan sesuai dengan urutan proses yang sudah ditetapkan.</li>
                            <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada).</li>
                        </ul>
                    </div>

                    {{-- SKENARIO + TABEL UNIT --}}
                    <div class="mb-10 p-6 bg-white rounded-lg shadow-lg">

                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">B. Skenario Tugas Praktik
                            Demonstrasi</h2>

                        {{-- TABEL UNIT KOMPETENSI --}}
                        <div class="border-2 border-gray-800 rounded-lg overflow-x-auto mb-8 shadow-md">

                            <table class="w-full text-left border-collapse min-w-[900px]">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="p-3 text-sm font-bold text-center border-r border-gray-600 w-1/12">
                                            No.
                                        </th>
                                        <th class="p-3 text-sm font-bold text-center border-r border-gray-600 w-1/4">
                                            Kode
                                            Unit</th>
                                        <th class="p-3 text-sm font-bold text-center">Judul Unit</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white text-gray-800">
                                    @forelse($daftarUnitKompetensi ?? [] as $unit)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                            <td
                                                class="p-3 text-center border-r border-gray-200 font-semibold align-top">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="p-3 font-mono border-r border-gray-200 text-center align-top">
                                                {{ $unit->kode_unit }}
                                            </td>
                                            <td class="p-3 align-top">
                                                {{ $unit->judul_unit }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-4 text-center text-gray-500 italic">Tidak ada
                                                unit
                                                kompetensi terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>

                        {{-- INPUT SKENARIO --}}
                        <div class="mb-6">
                            <label for="skenario" class="font-bold text-sm text-gray-700 block mb-2">Instruksi /
                                Skenario
                                *</label>

                            @if ($isAdmin)
                                <textarea id="skenario" name="skenario" rows="6"
                                    class="w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                {{ old('skenario', $ia02->skenario ?? '') }}
                            </textarea>
                            @else
                                <div
                                    class="bg-gray-50 border border-gray-300 rounded p-4 whitespace-pre-line min-h-[100px] text-gray-700 shadow-inner">
                                    {!! nl2br(e($ia02->skenario ?? '-')) !!}
                                </div>
                            @endif
                        </div>

                        {{-- PERALATAN --}}
                        <div class="mb-6">
                            <label for="peralatan" class="font-bold text-sm text-gray-700 block mb-2">Perlengkapan &
                                Peralatan *</label>

                            @if ($isAdmin)
                                <textarea id="peralatan" name="peralatan" rows="3"
                                    class="w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                {{ old('peralatan', $ia02->peralatan ?? '') }}
                            </textarea>
                            @else
                                <div
                                    class="bg-gray-50 border border-gray-300 rounded p-4 whitespace-pre-line text-gray-700 shadow-inner">
                                    {!! nl2br(e($ia02->peralatan ?? '-')) !!}
                                </div>
                            @endif
                        </div>

                        {{-- WAKTU --}}
                        <div class="mb-6">
                            <label for="waktu" class="font-bold text-sm text-gray-700 block mb-2">Waktu Pengerjaan
                                *</label>

                            @if ($isAdmin)
                                <input type="time" id="waktu" name="waktu"
                                    value="{{ old('waktu', isset($ia02->waktu) ? Carbon::parse($ia02->waktu)->format('H:i') : '') }}"
                                    class="border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 w-full md:w-1/3 transition duration-150 ease-in-out">
                            @else
                                <div
                                    class="bg-gray-50 border border-gray-300 rounded p-3 text-gray-700 w-full md:w-1/3 font-mono shadow-inner">
                                    {{ isset($ia02->waktu) ? Carbon::parse($ia02->waktu)->format('H:i') : '-' }}
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- TANDA TANGAN (Ganti x-kolom_ttd.asesiasesor dengan div statis) --}}
                    <div class="mb-10 p-6 bg-white rounded-lg shadow-lg border-2 border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">C. Tanda Tangan Asesi dan Asesor
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            {{-- Kolom Asesi --}}
                            <div class="flex flex-col border p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-lg mb-4 text-center text-gray-700">Asesi</h3>
                                <div class="mb-2">
                                    <label class="font-medium text-gray-600">Nama Lengkap:</label>
                                    <p class="p-2 border rounded bg-gray-50">{{ $sertifikasi->asesi->nama ?? '-' }}</p>
                                </div>
                                <div class="mb-2">
                                    <label class="font-medium text-gray-600">Tanggal:</label>
                                    <p class="p-2 border rounded bg-gray-50">
                                        {{ Carbon::now()->isoFormat('D MMMM YYYY') }}
                                    </p>
                                </div>
                                <div
                                    class="flex-grow flex items-center justify-center h-32 bg-gray-200 rounded-lg text-gray-500 italic mt-4 border-dashed border-2">
                                    Tanda Tangan Digital Asesi
                                </div>
                            </div>

                            {{-- Kolom Asesor --}}
                            <div class="flex flex-col border p-4 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-lg mb-4 text-center text-gray-700">Asesor</h3>
                                <div class="mb-2">
                                    <label class="font-medium text-gray-600">Nama Lengkap:</label>
                                    <p class="p-2 border rounded bg-gray-50">{{ $sertifikasi->asesor->nama ?? '-' }}
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <label class="font-medium text-gray-600">No. Reg. MET:</label>
                                    <p class="p-2 border rounded bg-gray-50">{{ $sertifikasi->asesor->no_reg ?? '-' }}
                                    </p>
                                </div>
                                <div
                                    class="flex-grow flex items-center justify-center h-32 bg-gray-200 rounded-lg text-gray-500 italic mt-4 border-dashed border-2">
                                    Tanda Tangan Digital Asesor
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL --}}
                    <div class="flex justify-end gap-4 pb-10">

                        <a href="{{ route('daftar_asesi') }}"
                            class="px-6 py-2 border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-medium rounded-full shadow-md transition duration-150 ease-in-out">
                            Kembali
                        </a>

                        @if ($isAdmin)
                            <button type="submit"
                                class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition duration-150 ease-in-out transform hover:scale-105 flex items-center gap-2">
                                Simpan Instruksi <span class="text-xl">✓</span>
                            </button>
                        @endif

                    </div>

                </form>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 p-6 text-center rounded-lg shadow-sm">
                    <h2 class="text-xl font-bold mb-2">Data Sertifikasi Tidak Ditemukan</h2>
                    <a href="{{ route('daftar_asesi') }}" class="text-blue-600 font-bold hover:underline">Kembali ke
                        Daftar Asesi</a>
                </div>
            @endif

        </div>
</body>

</html>
