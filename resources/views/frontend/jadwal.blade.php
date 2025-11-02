@extends('layouts.app-profil')
@section('content')

<!-- KONTEN TABEL -->
<main class="container mx-auto px-6 mt-20 mb-12">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Jadwal Asesmen
    </h1>

    <div class="bg-amber-50 shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-amber-200 text-gray-800">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Skema Sertifikasi</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-center">Waktu</th>
                    <th class="py-3 px-4 text-center">Ruangan</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">

                <tr class="border-b hover:bg-amber-100">
                    <td class="py-3 px-4">1</td>
                    <td class="py-3 px-4">Junior Web Dev</td>
                    <td class="py-3 px-4">12 November 2025</td>
                    <td class="py-3 px-4 text-center">09.00 - 11.00</td>
                    <td class="py-3 px-4 text-center">Lab 1</td>
                    <td class="py-3 px-4 text-center space-x-2">
                        <a href="{{ route('daftar_asesi') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                            Lihat
                        </a>
                    </td>
                </tr>

                <tr class="border-b hover:bg-amber-100">
                    <td class="py-3 px-4">2</td>
                    <td class="py-3 px-4">Data Science</td>
                    <td class="py-3 px-4">15 November 2025</td>
                    <td class="py-3 px-4 text-center">10.00 - 12.00</td>
                    <td class="py-3 px-4 text-center">Lab 2</td>
                    <td class="py-3 px-4 text-center space-x-2">
                        <a href="#" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                            Lihat
                        </a>
                    </td>
                </tr>

                <tr class="border-b hover:bg-amber-100">
                    <td class="py-3 px-4">3</td>
                    <td class="py-3 px-4">Game Dev</td>
                    <td class="py-3 px-4">17 November 2025</td>
                    <td class="py-3 px-4 text-center">13.00 - 15.00</td>
                    <td class="py-3 px-4 text-center">Lab 3</td>
                    <td class="py-3 px-4 text-center space-x-2">
                        <a href="#" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                            Lihat
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-amber-100">
                    <td class="py-3 px-4">4</td>
                    <td class="py-3 px-4">Cyber Security</td>
                    <td class="py-3 px-4">20 November 2025</td>
                    <td class="py-3 px-4 text-center">09.00 - 11.00</td>
                    <td class="py-3 px-4 text-center">Lab 4</td>
                    <td class="py-3 px-4 text-center space-x-2">
                        <a href="#" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                            Lihat
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

    <!-- SCRIPT DROPDOWN -->
    <script>
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

@endsection