<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Asesi | {{ $asesi->nama_lengkap }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
        ::-webkit-scrollbar { width: 0; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <x-navbar.navbar-admin />

    <main class="p-8">

        {{-- Breadcrumb & Header --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                @if($sertifikasiAcuan && $sertifikasiAcuan->jadwal && $sertifikasiAcuan->jadwal->skema)
                    <a href="{{ route('admin.apl01.show', $sertifikasiAcuan->jadwal->skema->id_skema) }}" class="hover:text-blue-600">Daftar Asesi</a> / 
                @endif
                Detail Asesi
            </p>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                    {{ strtoupper(substr($asesi->nama_lengkap, 0, 1)) }}
                </div>
                <div>
                   <h2 class="text-2xl font-bold text-gray-900">{{ $asesi->nama_lengkap }}</h2>
                   <p class="text-sm text-gray-500">{{ $sertifikasiAcuan->jadwal->skema->nama_skema ?? 'Skema Tidak Ditemukan' }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Laporan Asesmen (Formulir)</h3>
                <span class="text-xs font-medium px-2 py-1 bg-blue-50 text-blue-600 rounded-md">
                    Total: {{ count($activeForms) }} Formulir
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4 border-b border-gray-200">Nama Formulir</th>
                            <th class="px-6 py-4 border-b border-gray-200">Laporan</th>
                            <th class="px-6 py-4 border-b border-gray-200 text-center">Status</th>
                            <th class="px-6 py-4 border-b border-gray-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($activeForms as $formCode)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Nama --}}
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $formCode }}
                                </td>

                                {{-- Laporan --}}
                                <td class="px-6 py-4 text-gray-600">
                                    Dokumen Laporan {{ $formCode }}
                                </td>

                                {{-- Status (Dummy Logic per Form) --}}
                                @php
                                    $status = 'Menunggu';
                                    $class = 'bg-yellow-100 text-yellow-700';
                                    
                                    // Logic Sederhana Status (Placeholder)
                                    // Bisa diganti dengan query real status per form jika ada di DB
                                    if(in_array($formCode, ['FR.APL.01', 'FR.APL.02'])) {
                                        $status = 'Tersedia';
                                        $class = 'bg-blue-100 text-blue-700';
                                    }
                                @endphp
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $class }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 font-semibold text-xs flex items-center justify-center mx-auto transition">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                    Tidak ada formulir yang aktif untuk skema ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>