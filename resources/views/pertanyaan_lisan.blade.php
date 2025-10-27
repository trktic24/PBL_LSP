<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat - Pertanyaan Lisan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    @php
        // Data skema - bisa diganti dengan data dari controller
        $currentPage = request()->get('page', 1); // Default page 1
        
        // Data Asesor (nanti bisa diambil dari database melalui controller)
        $asesor = [
            'nama' => 'Dr. John Doe, M.Kom',
            'no_reg' => 'MET-2024-00123'
        ];
        
        $skemaList = [
            1 => [
                'judul' => 'Menggunakan Struktur Data',
                'unit' => 1
            ],
            2 => [
                'judul' => 'Mengimplementasikan User Interface',
                'unit' => 2
            ],
            3 => [
                'judul' => 'Melakukan Instalasi Software Tools Pemrograman',
                'unit' => 3
            ],
            4 => [
                'judul' => 'Menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practice',
                'unit' => 4
            ],
            5 => [
                'judul' => 'Mengimplementasikan Pemrograman Terstruktur',
                'unit' => 5
            ],
            6 => [
                'judul' => 'Menggunakan Library atau Komponen Pre-existing',
                'unit' => 6
            ],
            7 => [
                'judul' => 'Membuat Dokumen Kode Program',
                'unit' => 7
            ],
            8 => [
                'judul' => 'Melakukan Debugging',
                'unit' => 8
            ],
        ];
        
        $totalSkema = count($skemaList);
        $currentSkema = $skemaList[$currentPage] ?? $skemaList[1];
    @endphp

    <div class="flex min-h-screen">
        <!-- Sidebar Kiri -->
        <x-sidebar2></x-sidebar2>

        <!-- Main Content -->
        <main class="flex-1 bg-white p-10">
            <h1 class="text-4xl font-bold mb-8 text-center">Pertanyaan Lisan</h1>

            <hr class="border-t-2 border-gray-300 mb-8">

            <!-- Form Section -->
            <div class="mb-8">
                <div class="grid grid-cols-20 gap-6 mb-6">
                    <div class="flex items-center">
                        <label class="w-40 font-medium">TUK</label>
                        <span class="mr-4">:</span>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="tuk" value="sewaktu" class="mr-2" checked>
                                <span>Sewaktu</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tuk" value="tempat_kerja" class="mr-2">
                                <span>Tempat Kerja</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tuk" value="mandiri" class="mr-2">
                                <span>Mandiri</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center">
                        <label class="w-40 font-medium">Nama Asesor</label>
                        <span class="mr-4">:</span>
                        <span class="text-gray-700">{{ $asesor['nama'] }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center">
                        <label class="w-40 font-medium">No. Reg. Met</label>
                        <span class="mr-4">:</span>
                        <span class="text-gray-700">{{ $asesor['no_reg'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-2">{{ $currentSkema['judul'] }}</h2>
                <p class="text-sm text-gray-600 mb-4">Unit Kompetensi {{ $currentSkema['unit'] }} dari {{ $totalSkema }}</p>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="border border-gray-400 px-4 py-3 text-center w-16"></th>
                                <th class="border border-gray-400 px-4 py-3 text-center">Pertanyaan</th>
                                <th class="border border-gray-400 px-4 py-3 text-center w-24">Jawaban Asesi</th>
                                <th class="border border-gray-400 px-4 py-3 text-center w-16">K</th>
                                <th class="border border-gray-400 px-4 py-3 text-center w-16">BK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="border border-gray-300 px-4 py-3 text-center">1</td>
                                <td class="border border-gray-300 px-4 py-3"></td>
                                <td class="border border-gray-300 px-4 py-3"></td>
                                <td class="border border-gray-300 px-4 py-3 text-center">
                                    <input type="radio" name="jawaban_1" value="k" class="w-5 h-5">
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-center">
                                    <input type="radio" name="jawaban_1" value="bk" class="w-5 h-5">
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="border border-gray-300 px-4 py-16"></td>
                                <td class="border border-gray-300 px-4 py-16"></td>
                                <td class="border border-gray-300 px-4 py-16"></td>
                                <td class="border border-gray-300 px-4 py-16 text-center">
                                    <input type="radio" name="jawaban_2" value="k" class="w-5 h-5">
                                </td>
                                <td class="border border-gray-300 px-4 py-16 text-center">
                                    <input type="radio" name="jawaban_2" value="bk" class="w-5 h-5">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center mt-8">
                @if($currentPage > 1)
                    <a href="?page={{ $currentPage - 1 }}" class="px-8 py-2 bg-gray-300 text-gray-700 rounded-full font-medium hover:bg-gray-400 transition">
                        Sebelumnya
                    </a>
                @else
                    <button disabled class="px-8 py-2 bg-gray-200 text-gray-400 rounded-full font-medium cursor-not-allowed">
                        Sebelumnya
                    </button>
                @endif

                @if($currentPage < $totalSkema)
                    <a href="?page={{ $currentPage + 1 }}" class="px-8 py-2 bg-blue-500 text-white rounded-full font-medium hover:bg-blue-600 transition">
                        Selanjutnya
                    </a>
                @else
                    <button class="px-8 py-2 bg-green-500 text-white rounded-full font-medium hover:bg-green-600 transition">
                        Selesai
                    </button>
                @endif
            </div>
        </main>
    </div>
</body>
</html>