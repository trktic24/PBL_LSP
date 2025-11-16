@extends('layouts.app-profil')

@section('title', $jadwal->skema->nama_skema ?? 'Detail Jadwal')

@section('content')

{{-- Font Awesome (Jika belum ada di layout utama) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8 pt-24 max-w-6xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-4">{{ $jadwal->skema->nama_skema ?? 'Detail Jadwal' }}</h1>
            <div class="flex flex-wrap gap-6 text-gray-900 text-sm">
                
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar"></i>
                    <span>{{ $jadwal->tanggal_pelaksanaan ? $jadwal->tanggal_pelaksanaan->format('d F Y') : 'N/A' }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span>{{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('H:i') : 'N/A' }} - {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('H:i') : 'N/A' }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $jadwal->tuk?->nama_tuk ?? 'TUK belum diatur' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-gray-900 mb-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                
                <div class="mb-12">
                    <h2 class="text-xl font-bold mb-3">Deskripsi Skema</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $jadwal->skema->deskripsi ?? 'Deskripsi skema belum tersedia.' }}</p>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-3">Persyaratan Peserta</h2>
                    <ul class="list-disc list-inside text-gray-700 leading-relaxed">
                        <li>Terbuka untuk umum</li>
                        <li>Memiliki pengetahuan dasar terkait skema</li>
                        <li>Membawa laptop saat pelaksanaan</li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-300 rounded-lg p-12" style="box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.12);">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-semibold text-gray-700">Harga :</span>
                        <span class="font-bold text-lg">RP. 500.000</span>
                    </div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="font-semibold text-gray-700">Jumlah Pendaftar :</span>
                        <span class="font-bold">50 Orang</span> {{-- Asumsi statis --}}
                    </div>
                    
                    <button id="btn-daftar-sekarang"
                            data-jadwal-id="{{ $jadwal->id_jadwal }}"
                            class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                        Daftar Sekarang
                    </button>
                    
                    <p class="text-center text-gray-500 text-xs">
                        Pendaftaran ditutup tanggal 20 November 2025
                    </p>
                </div>
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