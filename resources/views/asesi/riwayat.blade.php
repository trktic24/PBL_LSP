<x-app-layout>

    {{-- Spacer untuk navbar fixed --}}
    <div class="h-[80px]"></div>

    <div class="container max-w-7xl mx-auto py-12 px-6">

        {{-- Judul Halaman --}}
        <h1 class="text-3xl font-bold text-gray-900 mb-8">
            Riwayat Sertifikasi Anda
        </h1>

        {{-- Kontainer Tabel (Card Putih) --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">

                {{-- Header Tabel --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Skema Kompetensi
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Asesmen
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-4">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>

                {{-- Body Tabel (Contoh Data Statis) --}}
                <tbody class="bg-white divide-y divide-gray-200">

                    {{-- CONTOH 1: Status Lulus (Kompeten) --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                Junior Web Developer
                            </div>
                            <div class="text-sm text-gray-500">
                                SKM.JWD.01
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                25 Oktober 2025
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Kompeten
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                                Download Sertifikat
                            </a>
                        </td>
                    </tr>

                    {{-- CONTOH 2: Status Gagal (Belum Kompeten) --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                Cybersecurity Analyst
                            </div>
                            <div class="text-sm text-gray-500">
                                SKM.CYB.03
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                15 September 2025
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Belum Kompeten
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{-- Tidak ada aksi --}}
                        </td>
                    </tr>

                    {{-- CONTOH 3: Status Proses --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                UI/UX Designer
                            </div>
                            <div class="text-sm text-gray-500">
                                SKM.UIX.01
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                30 November 2025
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Jadwal
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{-- Tidak ada aksi --}}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{--
          Tampilkan bagian ini jika data RIWAYAT KOSONG.
          Hapus bagian <tbody> di atas dan uncomment bagian <tbody> di bawah ini.
        --}}

        {{-- <tbody class="bg-white">
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum Ada Riwayat Sertifikasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Anda belum pernah mengambil atau sedang dalam proses sertifikasi.</p>
                    </div>
                </td>
            </tr>
        </tbody> --}}

        {{-- Link Pagination Statis (Hanya untuk Tampilan) --}}
        <div class="mt-8 flex justify-between items-center text-sm text-gray-700">
            <p>
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">3</span> dari <span class="font-medium">3</span> hasil
            </p>
            <div class="flex space-x-1">
                <span class="px-3 py-1 border rounded-md bg-gray-200 text-gray-500 cursor-not-allowed">Previous</span>
                <span class="px-3 py-1 border rounded-md bg-blue-600 text-white">1</span>
                <span class="px-3 py-1 border rounded-md bg-gray-200 text-gray-500 cursor-not-allowed">Next</span>
            </div>
        </div>

    </div>
</x-app-layout>
