<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Asesmen - FAP Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Asesmen</h1>
            <p class="text-gray-600">Lorem ipsum dolor<br>sit amet</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="max-w-5xl mx-auto px-1 sm:px-90 lg:px-8 py-8 text-center">
        <div class="bg-yellow-50 rounded-t-[3rem] shadow-md overflow-x-auto border border-gray-200 whitespace-nowrap">
            <!-- Table Header -->
            <div class="grid grid-cols-5 bg-yellow-50 border-b-2 border-gray-900">
                <div class="px-10 py-4 text-sm font-bold text-gray-900 text-center">Skema Sertifikasi</div>
                <div class="px-7 py-4 text-sm font-bold text-gray-900 text-center">Pendaftaran</div>
                <div class="px-14 py-4 text-sm font-bold text-gray-900 text-center">Tanggal Asesmen</div>
                <div class="px-20 py-4 text-sm font-bold text-gray-900 text-center">TUK</div>
                <div class="px-2 py-4 text-sm font-bold text-gray-900 text-center">Status</div>
            </div>

            <!-- Table Body -->
            @php
                $jadwalList = [
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Dibuka', 'statusColor' => 'text-teal-700', 'statusBg' => 'bg-teal-100'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Full', 'statusColor' => 'text-yellow-700', 'statusBg' => 'bg-yellow-200'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Selesai', 'statusColor' => 'text-gray-700', 'statusBg' => 'bg-gray-200'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Akan datang', 'statusColor' => 'text-blue-700', 'statusBg' => 'bg-blue-100'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Dibuka', 'statusColor' => 'text-teal-700', 'statusBg' => 'bg-teal-100'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Dibuka', 'statusColor' => 'text-teal-700', 'statusBg' => 'bg-teal-100'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Dibuka', 'statusColor' => 'text-teal-700', 'statusBg' => 'bg-teal-100'],
                    ['skema' => 'Junior Web Dev', 'pendaftaran' => '1 - 15 Oktober 2025', 'tanggal' => '25 Oktober 2025', 'tuk' => 'Polines', 'status' => 'Dibuka', 'statusColor' => 'text-teal-700', 'statusBg' => 'bg-teal-100'],
                ];
            @endphp

            @foreach($jadwalList as $index => $jadwal)
                <div class="grid grid-cols-5 border-b border-gray-200 bg-yellow-50">
                    <div class="px-10 py-4 text-sm text-gray-900 text-left">{{ $jadwal['skema'] }}</div>
                    <div class="px-7 py-4 text-sm text-gray-900 text-center">{{ $jadwal['pendaftaran'] }}</div>
                    <div class="px-14 py-4 text-sm text-gray-900 text-center">{{ $jadwal['tanggal'] }}</div>
                    <div class="px-20 py-4 text-sm text-gray-900 text-center">{{ $jadwal['tuk'] }}</div>
                    <div class="px-2 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap {{ $jadwal['statusColor'] }} {{ $jadwal['statusBg'] }}">{{ $jadwal['status'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>