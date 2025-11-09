@extends('layouts.app-profil')
@section('content')
{{-- Carbon tidak lagi digunakan di sini, karena pemformatan tanggal akan dilakukan oleh JavaScript --}}

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
                {{-- 
                MODIFIKASI 1: 
                Kita hapus loop @forelse dan beri ID pada <tbody>
                agar JavaScript bisa menemukannya.
                --}}
                <tbody id="jadwal-table-body" class="text-gray-700">
                    {{-- Kita tampilkan status "Memuat..." sebagai placeholder --}}
                    <tr>
                        <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                            Sedang memuat data jadwal...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
</main>

<!-- SCRIPT DROPDOWN (Ini adalah skrip Anda yang sudah ada, biarkan saja) -->
<script>
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');

    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });
    }

    window.addEventListener('click', function(e) {
        if (dropdownMenu && !dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>


{{-- 
MODIFIKASI 2: 
Tambahkan skrip baru ini untuk memanggil API Anda.
--}}
<script>
    // Jalankan skrip ini setelah halaman HTML selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Panggil API yang sudah kita buat
        // (Pastikan URL '/api/jadwal-asesmen' sesuai dengan yang ada di routes/api.php)
        fetch('/jadwal-asesmen')
            .then(response => response.json())
            .then(result => {
                // 2. Dapatkan elemen <tbody> dari tabel
                const tableBody = document.getElementById('jadwal-table-body');
                
                // Kosongkan <tbody> dari tulisan "Sedang memuat data..."
                tableBody.innerHTML = '';

                // 3. Cek jika data yang dikembalikan kosong
                if (result.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                                Belum ada jadwal asesmen yang tersedia.
                            </td>
                        </tr>
                    `;
                    return; // Hentikan eksekusi
                }

                // 4. Loop setiap data (item) dari JSON dan buat baris tabel (HTML)
                let counter = 1;
                result.data.forEach(item => {
                    
                    // Format tanggal menggunakan JavaScript (menggantikan Carbon PHP)
                    const waktuMulai = new Date(item.waktu_mulai).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    const tanggal = new Date(item.tanggal_pelaksanaan).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

                    // Ganti 'route(...)' dengan URL manual
                    // Pastikan URL '/daftar_asesi/' sudah benar sesuai routes/web.php Anda
                    const urlLihat = `/daftar_asesi/${item.id}`; 

                    // Buat baris HTML baru
                    const row = `
                        <tr class="border-b hover:bg-amber-100">
                            <td class="py-3 px-4">${counter++}</td>
                            <td class="py-3 px-4">${item.skema ? item.skema.nama_skema : 'N/A'}</td>
                            <td class="py-3 px-4">${item.sesi ?? 'N/A'}</td>
                            <td class="py-3 px-4 text-center">${waktuMulai}</td>
                            <td class="py-3 px-4 text-center">${tanggal}</td>
                            <td class="py-3 px-4 text-center">${item.Status_jadwal ?? 'N/A'}</td>
                            <td class="py-3 px-4 text-center">${item.tuk ? item.tuk.nama_lokasi : 'N/A'}</td>
                            <td class="py-3 px-4 text-center">${item.jenisTuk ? item.jenisTuk.jenis_tuk : 'N/A'}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <a href="${urlLihat}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium transition">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    `;
                    
                    // Tambahkan baris HTML baru ke dalam <tbody>
                    tableBody.innerHTML += row;
                });

            })
            .catch(error => {
                // Tampilkan error jika API gagal dipanggil
                console.error('Error fetching data:', error);
                const tableBody = document.getElementById('jadwal-table-body');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="py-4 px-4 text-center text-red-500">
                            Gagal memuat data. Silakan coba lagi nanti.
                        </td>
                    </tr>
                `;
            });
    });
</script>

@endsection
