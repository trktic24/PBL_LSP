<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Banding</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <x-sidebar2></x-sidebar2>

    <!-- Konten utama -->
    <main class="flex-1 p-10 overflow-y-auto">

        <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-xl">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Detail Banding</h1>

            <!-- Informasi Asesi -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Asesi</h2>

                <p class="text-gray-700">
                    Nama Asesi: {{ $banding->dataSertifikasiAsesi->asesi->nama_lengkap ?? '-' }}
                </p>

                <p class="text-gray-700">
                    ID Data Sertifikasi: {{ $banding->id_data_sertifikasi_asesi }}
                </p>

                <p class="text-gray-700">
                    Tanggal Pengajuan: {{ $banding->tanggal_pengajuan_banding }}
                </p>
            </div>

            <!-- Tabel Detail Banding -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">Komponen</th>
                            <th class="px-6 py-3 text-center w-32">Nilai</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4">TUK Sewaktu</td>
                            <td class="px-6 py-4 text-center">{{ $banding->tuk_sewaktu ? 'Ya' : 'Tidak' }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">TUK Tempat Kerja</td>
                            <td class="px-6 py-4 text-center">{{ $banding->tuk_tempatkerja ? 'Ya' : 'Tidak' }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">TUK Mandiri</td>
                            <td class="px-6 py-4 text-center">{{ $banding->tuk_mandiri ? 'Ya' : 'Tidak' }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">Apakah Proses Banding Dijelaskan?</td>
                            <td class="px-6 py-4 text-center">{{ $banding->ya_tidak_1 }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">Apakah Berdiskusi Dengan Asesor?</td>
                            <td class="px-6 py-4 text-center">{{ $banding->ya_tidak_2 }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">Apakah Melibatkan Orang Lain?</td>
                            <td class="px-6 py-4 text-center">{{ $banding->ya_tidak_3 }}</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4">Alasan Banding</td>
                            <td class="px-6 py-4 text-center">{{ $banding->alasan_banding }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tanda Tangan Asesi -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Tanda Tangan Asesi</h2>
                @if($banding->tanda_tangan_asesi)
                    <img src="{{ $banding->tanda_tangan_asesi }}" alt="Tanda Tangan Asesi" class="border border-gray-300 rounded-lg w-64 h-32 object-contain">
                @else
                    <p class="text-gray-500">Belum ada tanda tangan.</p>
                @endif
            </div>

            <!-- Tombol Kembali -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('terimakasih_banding', ['id' => $banding->id_banding]) }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold">
                    Kembali
                </a>
            </div>

        </div>

    </main>

</body>
</html>