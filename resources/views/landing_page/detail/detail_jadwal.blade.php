@extends('layouts.app-profil')

@section('title', $jadwal->skema->nama_skema ?? 'Detail Jadwal')

@section('content')

    {{-- Font Awesome (Jika belum ada di layout utama) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8 pt-24 max-w-6xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        {{-- Judul Skema --}}
        <h1 class="text-3xl font-bold mb-4 font-poppins">{{ $jadwal->skema?->nama_skema ?? 'Nama Skema Tidak Ditemukan' }}</h1>
        
        <div class="mb-6">
            <div class="flex flex-wrap gap-6 text-gray-900 text-sm">
                
                {{-- Tanggal Pelaksanaan --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar"></i>
                    <span>
                        {{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d F Y') : 'Tanggal Belum Diatur' }}
                    </span>
                </div>

                {{-- Waktu --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span>{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : '00:00' }} - Selesai</span>
                </div>

                {{-- Lokasi TUK --}}
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $jadwal->masterTuk?->nama_lokasi ?? 'TUK Tidak Ditemukan' }}</span>
                </div>
            </div>
        </div>

            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-4">{{ $jadwal->skema->nama_skema ?? 'Detail Jadwal' }}</h1>
                <div class="flex flex-wrap gap-6 text-gray-900 text-sm">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                
                {{-- Deskripsi Skema (Tetap Ambil dari Database) --}}
                <div class="mb-12">
                    <h2 class="text-xl font-bold mb-3 font-poppins">Deskripsi Skema</h2>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        {{ $jadwal->skema?->deskripsi_skema ?? 'Deskripsi skema belum tersedia saat ini.' }}
                    </p>
                </div>

                {{-- 🟦 PERBAIKAN: PERSYARATAN PESERTA (STATIS) --}}
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-3 font-poppins">Persyaratan Peserta</h2>
                    <div class="text-gray-600 leading-relaxed">
                        <ul class="list-disc list-inside space-y-2">
                            <li>Kartu Tanda Pengenal(KTP/PASPOR/KTM).</li>
                            <li>Pas foto berwarna (background merah/biru).</li>
                            <li>Curriculum Vitae (CV) terbaru.</li>
                            <li>Fotocopy Ijazah terakhir atau Transkrip Nilai sementara.</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-300 rounded-lg p-12" style="box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.12);">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-semibold text-gray-700">Harga :</span>
                        <span class="font-bold text-lg">RP. {{ number_format($jadwal->skema?->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="font-semibold text-gray-700">Jumlah Pendaftar :</span>
                        <span class="font-bold">{{ $jumlahPeserta ?? 0 }} Orang</span>
                    </div>
                    <button id="btn-daftar-sekarang" data-jadwal-id="{{ $jadwal->id_jadwal }}"
                        class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                        Daftar Sekarang
                    </button>
                    
                    <p class="text-center text-gray-500 text-xs">
                        Pendaftaran ditutup tanggal 
                        {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Ambil CSRF Token
            const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenEl) {
                console.error('Meta tag CSRF token tidak ditemukan!');
                return;
            }
            const csrfToken = csrfTokenEl.getAttribute('content');

            // 2. Ambil Tombol
            const daftarButton = document.getElementById('btn-daftar-sekarang');

            if (daftarButton) {
                // 3. Tambahkan Event Listener
                daftarButton.addEventListener('click', function() {

                    const jadwalId = this.dataset.jadwalId;

                    // [DIHAPUS] Notifikasi konfirmasi "Anda yakin..."
                    // if (!confirm('Anda yakin ingin mendaftar ke jadwal ini?')) {
                    //     return; 
                    // }

                    this.disabled = true;
                    this.innerText = 'Memproses...';

                    // 4. Panggil API Pendaftaran
                    fetch('{{ route('api.jadwal.daftar') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                id_jadwal: jadwalId
                            })
                        })
                        .then(response => {
                            // 401 = Belum login
                            if (response.status === 401) {
                                // Jika belum login, langsung arahkan ke halaman login
                                window.location.href = '{{ route('login') }}';
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data) return;

                            // [DIHAPUS] Notifikasi "Berhasil mendaftar..." / "Anda sudah memiliki..."
                            // alert(data.message); 

                            // Cek jika ada URL redirect (baik sukses atau gagal karena sudah terdaftar)
                            if (data.redirect_url) {
                                // Langsung redirect ke tracker
                                window.location.href = data.redirect_url;
                            } else {
                                // Jika ada error lain (server error), tampilkan alert
                                alert(data.message);
                                this.disabled = false;
                                this.innerText = 'Daftar Sekarang';
                            }
                        })
                        .catch(error => {
                            // Tampilkan alert jika ada error jaringan
                            console.error('Error:', error);
                            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                            this.disabled = false;
                            this.innerText = 'Daftar Sekarang';
                        });
                });
            }
        });
    </script>
@endpush
