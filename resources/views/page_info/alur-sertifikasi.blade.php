<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Asesor - FAP Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Asesor</h1>
            <p class="text-gray-600">Lorem ipsum dolor<br>sit amet</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Box -->
        <div class="flex justify-end mb-4">
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search" 
                    class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                    onkeyup="filterTable()"
                >
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-t-[3rem] shadow-md overflow-x-auto border border-gray-200">
            <table class="w-full">
                <!-- Table Header -->
                <thead class="bg-yellow-50 border-b-2 border-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Nama Asesor</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center">No. Registrasi</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Bidang Keahlian</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Provinsi</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Jumlah Asesmen</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                @php
                    $daftarList = [
                        ['nama_asesor' => 'Kurniawan S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001234', 'bidang_keahlian' => 'Teknik Jaringan & Cybersecurity', 'provinsi' => 'Jawa Tengah', 'jumlah_asesmen' => '100'],
                        ['nama_asesor' => 'Apipi S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001235', 'bidang_keahlian' => 'Artificial Intelligence', 'provinsi' => 'Jawa Barat', 'jumlah_asesmen' => '85'],
                        ['nama_asesor' => 'Sucipto Krispi S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001236', 'bidang_keahlian' => 'Data Science', 'provinsi' => 'DKI Jakarta', 'jumlah_asesmen' => '120'],
                        ['nama_asesor' => 'Jajang Sokbreker S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001237', 'bidang_keahlian' => 'Internet of Things (IoT)', 'provinsi' => 'Jawa Timur', 'jumlah_asesmen' => '95'],
                        ['nama_asesor' => 'Yanto Kopling S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001238', 'bidang_keahlian' => 'Software Engineering', 'provinsi' => 'Banten', 'jumlah_asesmen' => '78'],
                        ['nama_asesor' => 'Dimas Rachman S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001239', 'bidang_keahlian' => 'Cloud Computing & DevOps', 'provinsi' => 'Yogyakarta', 'jumlah_asesmen' => '90'],
                        ['nama_asesor' => 'Nanda Permana S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001240', 'bidang_keahlian' => 'Machine Learning', 'provinsi' => 'Sumatera Barat', 'jumlah_asesmen' => '110'],
                        ['nama_asesor' => 'Fitri Handayani S.Kom., M.Kom.', 'no_registrasi' => 'MET.000.001241', 'bidang_keahlian' => 'Database Administration', 'provinsi' => 'Kalimantan Selatan', 'jumlah_asesmen' => '82'],
                    ];
                @endphp

                <tbody id="tableBody">
                    @foreach($daftarList as $index => $daftar)
                        <tr class="table-row border-b border-gray-200 bg-yellow-50 hover:bg-yellow-100 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 text-left">{{ $daftar['nama_asesor'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $daftar['no_registrasi'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $daftar['bidang_keahlian'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $daftar['provinsi'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $daftar['jumlah_asesmen'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('.table-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>