@extends('layouts.app-profil')
@section('content')

<!-- KONTEN TABEL -->
<main class="container mx-auto px-6 mt-20 mb-12">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Jadwal Asesmen
    </h1>

    <div class="bg-amber-50 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-amber-200 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Skema</th>
                        <th class="py-3 px-4 text-left">Sesi</th>
                        <th class="py-3 px-4 text-center">Waktu Mulai</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">TUK</th>
                        <th class="py-3 px-4 text-center">Jenis TUK</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- Loop data dari controller --}}
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b hover:bg-amber-100">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->nama_skema ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $jadwal->sesi ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->waktu_mulai ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tanggal ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->status ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tuk ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->jenis_tuk ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <a href="{{ route('daftar_asesi', $jadwal->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampil jika $jadwals kosong --}}
                        <tr>
                            <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                                Belum ada jadwal asesmen yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>    
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